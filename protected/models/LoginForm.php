<?php

declare(strict_types=1);

class LoginForm extends CFormModel
{
    public string $username = '';
    public string $password = '';

    private ?UserIdentity $_identity = null;

    public function rules(): array
    {
        return [
            ['username, password', 'required'],
            ['password', 'authenticate'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Login',
            'password' => 'Password',
        ];
    }

    public function authenticate(string $attribute, array $params): void
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            if (!$this->_identity->authenticate()) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            return Yii::app()->user->login($this->_identity, 0);
        }

        return false;
    }
}
