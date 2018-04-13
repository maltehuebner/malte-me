<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        $canMethodName = $this->getCanMethodName($attribute);

        if ($this->isUserMandatory($canMethodName) && !$user instanceof User) {
            return false;
        } elseif (!$this->isUserMandatory($canMethodName) && !$user instanceof User) {
            return $this->$canMethodName($subject, null, $user);
        } else {
            return $this->$canMethodName($subject, $user);
        }
    }

    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, $this->getAccessRightAttributes())) {
            return false;
        }

        $fqcn = $this->getFqcn();

        if (!$subject instanceof $fqcn) {
            return false;
        }

        return true;
    }

    protected function getAccessRightAttributes(): array
    {
        $reflection = new \ReflectionClass($this);

        $attributeList = [];

        foreach ($reflection->getMethods() as $method) {
            preg_match('/^can([A-Za-z]+)$/', $method->getName(), $matches);

            if (2 === count($matches)) {
                $attributeList[] = lcfirst(array_pop($matches));
            }
        }

        return $attributeList;
    }

    protected function getFqcn(): string
    {
        $voterClassname = get_class($this);

        preg_match('/(.*)\\\([A-Za-z].*)Voter/', $voterClassname, $matches);

        $entityClassName = array_pop($matches);

        $fqcn = sprintf('AppBundle\\Entity\\%s', $entityClassName);

        return $fqcn;
    }

    protected function getCanMethodName(string $attribute): string
    {
        return sprintf('can%s', ucfirst(strtolower($attribute)));
    }

    protected function isUserMandatory(string $methodName): bool
    {
        $reflection = new \ReflectionMethod($this, $methodName);
        $parameters = $reflection->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->getClass()->getName() === User::class) {
                return !$parameter->allowsNull();
            }
        }

        throw new \InvalidArgumentException('There must be a User accepting parameter');
    }
}
