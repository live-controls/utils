<?php

namespace LiveControls\Utils;

use Exception;
use LiveControls\Utils\Enums\SocialSecurityNumberTypes;

class SocialSecurity
{
    /**
     * Formats a numeric only representation 11222333000181 of a CNPJ to a human readable one 11.222.333/0001-81
     *
     * @param string $cnpjNumeric
     * @return string
     */
    public static function formatCNPJ(string $cnpjNumeric): string 
    {
        $cnpjNumeric = Numbers::toNumeric($cnpjNumeric);
        $cnpjStr = str_pad($cnpjNumeric, 14, '0', STR_PAD_LEFT);
        if (strlen($cnpjStr) !== 14 || !ctype_digit($cnpjStr)) {
            throw new Exception("Invalid CNPJ!");
        }
        return substr($cnpjStr, 0, 2) . '.' . substr($cnpjStr, 2, 3) . '.' . substr($cnpjStr, 5, 3) . '/' . substr($cnpjStr, 8, 4) . '-' . substr($cnpjStr, 12, 2);
    }

    /**
     * Formats a numeric only representation 12345678900 of a CPF to a human readable one 123.456.789-00
     *
     * @param string $cpfNumeric
     * @return string
     */
    public static function formatCPF(string $cpfNumeric): string 
    {
        $cpfNumeric = Numbers::toNumeric($cpfNumeric);
        $cpfStr = str_pad($cpfNumeric, 11, '0', STR_PAD_LEFT);
        if (strlen($cpfStr) !== 11 || !ctype_digit($cpfStr)) {
            throw new Exception("Invalid CPF!");
        }
        return substr($cpfStr, 0, 3) . '.' . substr($cpfStr, 3, 3) . '.' . substr($cpfStr, 6, 3) . '-' . substr($cpfStr, 9, 2);
    }

    /**
     * Checks if the CPF/CNPJ number is valid
     *
     * @param string $cpfCnpj
     * @return boolean
     */
    public static function isValidCPFCNPJ(string $cpfCnpj): bool
    {
        return static::isValidCPF($cpfCnpj) || static::isValidCNPJ($cpfCnpj);
    }

    /**
     * Checks if the CPF number is valid
     *
     * @param string $cpf
     * @return boolean
     */
    public static function isValidCPF(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf); // Remove non-numeric characters
        if(strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)){
            return false; // Invalid length or repeated digits (00000000000, 11111111111, etc.)
        }

        // First digit calculation
        $sum = 0;
        for($i = 0; $i < 9; $i++){
            $sum += (int)$cpf[$i] * (10 - $i);
        }
        $remainder = $sum % 11;
        $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        if((int)$cpf[9] !== $firstDigit){
            return false;
        }

        // Second digit calculation
        $sum = 0;
        for($i = 0; $i < 10; $i++){
            $sum += (int)$cpf[$i] * (11 - $i);
        }
        $remainder = $sum % 11;
        $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;

        return (int)$cpf[10] === $secondDigit;
    }

    /**
     * Checks if the CNPJ number is valid
     *
     * @param string $cnpj
     * @return boolean
     */
    public static function isValidCNPJ(string $cnpj)
    {
        $cnpj = preg_replace('/\D/', '', $cnpj); // Remove non-numeric characters
        if(strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)){
            return false;
        }

        // Calculation of the first verification digit
        $weightsFirst = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for($i = 0; $i < 12; $i++){
            $sum += (int)$cnpj[$i] * $weightsFirst[$i];
        }
        $remainder = $sum % 11;
        $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        if((int)$cnpj[12] !== $firstDigit){
            return false;
        }

        // Calculation of the second verification digit
        $weightsSecond = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        for($i = 0; $i < 13; $i++){
            $sum += (int)$cnpj[$i] * $weightsSecond[$i];
        }
        $remainder = $sum % 11;
        $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;
        return (int) $cnpj[13] === $secondDigit;
    }

    /**
     * Returns the SocialSecurityNumberType of the $ssn string
     *
     * @param string $ssn
     * @return SocialSecurityNumberTypes
     */
    public static function getSocialSecurityNumberType(string $ssn): SocialSecurityNumberTypes
    {
        if(self::isValidCPF($ssn)){
            return SocialSecurityNumberTypes::CPF;
        }elseif(self::isValidCNPJ($ssn)){
            return SocialSecurityNumberTypes::CNPJ;
        }else{
            return SocialSecurityNumberTypes::INVALID;
        }
    }
}