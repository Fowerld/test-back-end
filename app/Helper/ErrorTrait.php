<?php

namespace App\Helper;

trait ErrorTrait
{
    /**
     * @var int
     */
    protected $error = 0;

    /**
     * @param int $error
     *
     * @return $this
     */
    public function setError(int $error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return int $error
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @param int $error
     *
     * @return $this
     */
    public function addError(int $error)
    {
        $this->error |= $error;

        return $this;
    }

    /**
     * @param int $error
     *
     * @return $this
     */
    public function removeError(int $error)
    {
        $this->error ^= $error;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !(0 === $this->error);
    }
}
