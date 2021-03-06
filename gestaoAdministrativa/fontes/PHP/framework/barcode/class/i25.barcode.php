<?php
/*
    **********************************************************************************
    *                                                                                *
    * @package URBEM CNM - Soluções em Gestão Pública                                *
    * @copyright (c) 2013 Confederação Nacional de Municípos                         *
    * @author Confederação Nacional de Municípios                                    *
    *                                                                                *
    * O URBEM CNM é um software livre; você pode redistribuí-lo e/ou modificá-lo sob *
    * os  termos  da Licença Pública Geral GNU conforme  publicada  pela Fundação do *
    * Software Livre (FSF - Free Software Foundation); na versão 2 da Licença.       *
    *                                                                                *
    * Este  programa  é  distribuído  na  expectativa  de  que  seja  útil,   porém, *
    * SEM NENHUMA GARANTIA; nem mesmo a garantia implícita  de  COMERCIABILIDADE  OU *
    * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral do GNU *
    * para mais detalhes.                                                            *
    *                                                                                *
    * Você deve ter recebido uma cópia da Licença Pública Geral do GNU "LICENCA.txt" *
    * com  este  programa; se não, escreva para  a  Free  Software Foundation  Inc., *
    * no endereço 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.       *
    *                                                                                *
    **********************************************************************************
*/
?>
<?php
/**
 * i25.barcode.php
 *--------------------------------------------------------------------
 *
 * Sub-Class - Interleaved 2 of 5
 *
 *--------------------------------------------------------------------
 * Revision History
 * v1.2.3b	31 dec	2005	Jean-Sébastien Goupil	Checksum separated + PHP5.1 compatible
 * v1.2.2	23 jul	2005	Jean-Sébastien Goupil	Correct Checksum
 * v1.2.1	27 jun	2005	Jean-Sébastien Goupil	Font support added
 * V1.00	17 jun	2004	Jean-Sebastien Goupil
 *--------------------------------------------------------------------
 * $Id: i25.barcode.php 59612 2014-09-02 12:00:51Z gelson $
 * PHP5-Revision: 1.7
 *--------------------------------------------------------------------
 * Copyright (C) Jean-Sebastien Goupil
 * http://other.lookstrike.com/barcode/
 */
class i25 extends BarCode
{
    public $keys = array(), $code = array();
    public $checksum;

    /**
     * Constructor
     *
     * @param int    $maxHeight
     * @param FColor $color1
     * @param FColor $color2
     * @param int    $res
     * @param string $text
     * @param mixed  $textfont  Font or int
     * @param bool   $checksum
     */
    public function i25($maxHeight, &$color1, &$color2, $res, $text, $textfont, $checksum = false)
    {
        BarCode::BarCode($maxHeight, $color1, $color2, $res);
        $this->keys = array('0','1','2','3','4','5','6','7','8','9');
        $this->code = array(
            '00110',	/* 0 */
            '10001',	/* 1 */
            '01001',	/* 2 */
            '11000',	/* 3 */
            '00101',	/* 4 */
            '10100',	/* 5 */
            '01100',	/* 6 */
            '00011',	/* 7 */
            '10010',	/* 8 */
            '01010'		/* 9 */
        );
        $this->setText($text);
        $this->setFont($textfont);
        $this->setDrawText(true);
        $this->checksum = (bool) $checksum;
    }

    /**
     * Saves Text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->checksumValue = false;		// Reset checksumValue
    }

    /**
     * Verifica se deve ou não escrever o texto abaixo do codigo de barras
     *
     * @param boolean $value
     */
    public function setDrawText($value)
    {
        $this->boDrawText = $value;
    }

    /**
     * Retorna o valor da variavel boDrawText
     *
     * @param boolean $value
     */
    public function getDrawText()
    {
        return $this->boDrawText;
    }

    /**
     * Draws the barcode
     *
     * @param resource $im
     */
    public function draw(&$im)
    {
        $error_stop = false;

        // Checking if all chars are allowed
        $c = strlen($this->text);
        for ($i = 0; $i < $c; $i++) {
            if (!is_int(array_search($this->text[$i], $this->keys))) {
                $this->DrawError($im, 'Char \'' . $this->text[$i] . '\' not allowed.');
                $error_stop = true;
            }
        }
        if ($error_stop === false) {
            // Must be even
            if ($c % 2 !== 0 && $this->checksum === false) {
                $this->DrawError($im, 'i25 must be even if checksum is false.');
                $error_stop = true;
            } elseif ($c % 2 === 0 && $this->checksum === true) {
                $this->DrawError($im, 'i25 must be odd if checksum is true.');
                $error_stop = true;
            }
            if ($error_stop === false) {
                $temp_text = $this->text;
                // Checksum
                if ($this->checksum === true) {
                    $this->calculateChecksum();
                    $temp_text .= $this->keys[$this->checksumValue];
                }
                // Starting Code
                $this->DrawChar($im, '0000', 1);
                // Chars
                $c = strlen($temp_text);
                for ($i = 0; $i < $c; $i += 2) {
                    $temp_bar = '';
                    $c2 = strlen($this->findCode($temp_text[$i]));
                    for ($j = 0; $j < $c2; $j++) {
                        $temp_bar .= substr($this->findCode($temp_text[$i]), $j, 1);
                        $temp_bar .= substr($this->findCode($temp_text[$i + 1]), $j, 1);
                    }
                    $this->DrawChar($im, $temp_bar, 1);
                }
                // Ending Code
                $this->DrawChar($im, '100', 1);
                $this->lastX = $this->positionX;
                $this->lastY = $this->maxHeight + $this->positionY;
                if ($this->getDrawText()) {
                    $this->DrawText($im);
                }
            }
        }
    }

    /**
     * Returns the maximal width of a barcode
     *
     * @return int
     */
    public function getMaxWidth()
    {
        $textlength = 7 * strlen($this->text) * $this->res;
        $startlength = 4 * $this->res;
        $checksumlength = 0;
        if ($this->checksum === true) {
            $checksumlength = 7 * $this->res;
        }
        $endlength = 4 * $this->res;

        return $startlength + $textlength + $checksumlength + $endlength;
    }

    /**
     * Overloaded method to calculate checksum
     */
    public function calculateChecksum()
    {
        // Calculating Checksum
        // Consider the right-most digit of the message to be in an "even" position,
        // and assign odd/even to each character moving from right to left
        // Even Position = 3, Odd Position = 1
        // Multiply it by the number
        // Add all of that and do 10-(?mod10)
        $even = true;
        $this->checksumValue = 0;
        $c = strlen($this->text);
        for ($i = $c; $i > 0; $i--) {
            if ($even === true) {
                $multiplier = 3;
                $even = false;
            } else {
                $multiplier = 1;
                $even = true;
            }
            $this->checksumValue += $this->keys[$this->text[$i - 1]] * $multiplier;
        }
        $this->checksumValue = (10 - $this->checksumValue % 10) % 10;
    }

    /**
     * Overloaded method to display the checksum
     */
    public function processChecksum()
    {
        if ($this->checksumValue === false) { // Calculate the checksum only once
            $this->calculateChecksum();
        }
        if ($this->checksumValue !== false) {
            return $this->keys[$this->checksumValue];
        }

        return false;
    }
};
