<?
/*
    A simple printing class for the POS58III - Line Thermal Printer

    Author: David Booth
    Date:   8/18/2013

    Hint: Permission problems try: chmod 666 /dev/usb/lp0

*/
class thermal_printer
{

    const CHARS_PER_LINE = 32;
    const NEWLINE        = "\x0A";
    const TRAIL_SPACE    = "\x1B\x4A\x9B";

    var $handle;

    /*
        Initialize printer
        =============================================================
        $path - Path to the printer ex. "/dev/usb/lp0"
    */
    function __construct($path) {

        $this->handle = fopen($path, "w");

    }

    /*
        Prints $string optionally followed by a newline
        =============================================================
        $string - String to print
        $newline - If TRUE the string will be followed by a newline 
    */
    function print_string($string, $newline = TRUE){

        fwrite($this->handle,$string);
        $this->print_newline($newline);
    }

    /*
        Prints a centered line, optionally followed by a newline
        =============================================================
        $string - String to print
        $newline - If TRUE the string will be followed by a newline 
    */
    function print_centered_string($string, $newline = TRUE){

        $len = strlen($string);
        $diff = (self::CHARS_PER_LINE - $len)/2;

        //Print leading spaces
        for($i = 0; $i<$diff; $i++){
            fwrite($this->handle," ");
        }

        fwrite($this->handle,$string);

        for($i = 0; $i<$diff; $i++){
            fwrite($this->handle," ");
        }

        $this->print_newline($newline);
    }


    /*
        Prints a horizontal rule, optionally followed by a newline
        =============================================================
        $char - character to use for the newline
        $newline - If TRUE the string will be followed by a newline 
    */
    function print_rule($char = '*', $newline = TRUE){

        for($i = 0; $i<self::CHARS_PER_LINE; $i++){
            fwrite($this->handle,$char);
        }   

        $this->print_newline($newline);
    }

    /*
        Prints a left aligned and right aligned string on the same line
        =============================================================
        $left_string  - String to be printed on the left
        $right_string - String to be printed on the right
        $newline - If TRUE the string will be followed by a newline 

    */
    function print_cart_item($left_string, $right_string, $newline = TRUE){
        
        $l_len        = strlen($left_string);
        $r_len        = strlen($right_string);
        $total_length = $l_len + $r_len;

        if( ($total_length) <= self::CHARS_PER_LINE){

            $diff = self::CHARS_PER_LINE - ($total_length);

            fwrite($this->handle,$left_string);

            for($i = 0; $i<$diff; $i++){
                fwrite($this->handle," ");
            }

            fwrite($this->handle,$right_string);

        }
        else{
            //Strings are too long, shorten left string
            $diff = self::CHARS_PER_LINE - $total_length - 5;
            $left_string = substr($left_string, 0, ($diff));
            $left_string .= "...";

            $this->print_cart_item($left_string, $right_string, $newline);
            return;




        }

        $this->print_newline($newline);
    }

    /*
        Prints a newline if $bool is TRUE
        =============================================================
        $bool - Determines if a newline should be printed

        This was implemented to clean up the above functions.
    */
    function print_newline($bool){
        if($bool){
            fwrite($this->handle,self::NEWLINE);
        }
    }

    /*
        Prints a Trailspace
        =============================================================

    */
    function print_trail_space(){
        fwrite($this->handle,self::TRAIL_SPACE);
    }




}

?>