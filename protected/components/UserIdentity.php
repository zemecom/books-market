<?php

declare(strict_types=1);

class UserIdentity extends CUserIdentity
{
    private ?User $user = null;
    private int $_id = 0;

    public function authenticate(): bool
    {
        $user = User::model()->findByAttributes(['login' => $this->username]);

        if ($user === null || !password_verify($this->password, $user->password_hash)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;

            return false;
        }

        $this->user = $user;
        $this->_id = (int) $user->id;
        $this->setState('login', $user->login);
        $this->errorCode = self::ERROR_NONE;

        return true;
    }

    public function getId(): int
    {
        return $this->_id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
