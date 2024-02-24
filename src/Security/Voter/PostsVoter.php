<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostsVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const UPDATE = 'POST_UPDATE';
    public const DELETE = 'POST_DELETE';
    private $security ; 

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    protected function supports(string $attribute, mixed $post): bool
    {
       
        return in_array($attribute, [self::EDIT, self::UPDATE,self::DELETE])
            && $post instanceof \App\Entity\Posts;
    }

    protected function voteOnAttribute(string $attribute, mixed $post, TokenInterface $token): bool
    {
        $user = $token->getUser();
     

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
              return  $this->canEdit();
                break;

            case self::UPDATE:
                return  $this->canUpdate();
              
                break;
            case self::DELETE:
                return  $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit()
    {
        return $this->security->isGranted('ROLE_USER');
    }
  
    private function canUpdate()
    {
        return $this->security->isGranted('ROLE_USER');
    }
    private function canDelete()
    {
        return $this->security->isGranted('ROLE_USER');
    }
}
