<?php

declare(strict_types=1);

namespace PhpCfdi\SatWsDescargaMasiva\Shared;

/**
 * Defines "CodEstatus" and "Mensaje"
 */
class StatusCode
{
    /** @var int */
    private $code;

    /** @var string */
    private $message;

    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * Contains the value of "CodEstatus"
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Contains the value of "Mensaje"
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Return true when "CodEstatus" is success
     * The only success code is "5000: Solicitud recibida con éxito"
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return (5000 === $this->code);
    }
}
