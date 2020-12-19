<?php
class angerhash {
    public function HashIt($string)
    {
        $return = '';
        $angerhashvalue = '';
        $algo = GetAlgo();
        $hashing = str_split($string, 1);
        foreach ($hashing as $part)
        {
            $return .= $algo[$part] . "|";
        }//alert('hello, world.');
        $angerhashvalue = rtrim($return, "|");
        echo '<script language="javascript">';
        echo 'alert("'.$angerhashvalue.'")';
        echo '</script>';
        die(rtrim($return, "|"));
    }

    public function UnhashIt($hash)
    {
        $return = '';
        $algo = GetAlgo();
        $unhash = explode("|", $hash);
        foreach ($unhash as $value)
        {
            $find = array_search($value, $algo);
            if ($find)
                $return .= $find;
        }
        return $return;
    }

    public function GetAlgo()
    {
        return [
            "a"=>"[Hy&Ü©]",
            "b"=>"[Hhe]",
            "c"=>"[Lhi]",
            "d"=>"[Be67]",
            "e"=>"[hB]",
            "f"=>"[C]",
            "g"=>"[htyN]",
            "h"=>"[hO]",
            "i"=>"[FFcK]",
            "j"=>"[Nye]",
            "k"=>"[5Na]",
            "l"=>"[Mg56]",
            "m"=>"[Al]",
            "n"=>"[Shgi]",
            "o"=>"[Py]",
            "p"=>"[S]",
            "q"=>"[Cl]",
            "r"=>"[Ahr]",
            "s"=>"[K]",
            "t"=>"[hCa]",
            "u"=>"[Sc]",
            "v"=>"[Thhi]",
            "w"=>"[V]",
            "x"=>"[Ch5r]",
            "y"=>"[Mn]",
            "z"=>"[Fe]",
            "A"=>"[C67o]",
            "B"=>"[N5i]",
            "C"=>"[Chyttu]",
            "D"=>"[Zhn]",
            "E"=>"[Giyta]",
            "F"=>"[Ge]",
            "G"=>"[Ahs]",
            "H"=>"[Se]",
            "I"=>"[B576hr]",
            "J"=>"[Kr]",
            "K"=>"[Rb]",
            "L"=>"[Shtyr]",
            "M"=>"[Ytyy]",
            "N"=>"[Zr]",
            "O"=>"[Nbty]",
            "P"=>"[Mo67]",
            "Q"=>"[Tc]",
            "R"=>"[Rghu]",
            "S"=>"[R7h]",
            "T"=>"[Pd]",
            "U"=>"[Ag]",
            "V"=>"[6Cd]",
            "W"=>"[In]",
            "X"=>"[Syun]",
            "Y"=>"[S5b]",
            "Z"=>"[Te]",
            " "=>"[KutJoch]",
            "1"=>"[Xe]",
            "2"=>"[Ctus]",
            "3"=>"[6Ba]",
            "4"=>"[Hf]",
            "5"=>"[Ta]",
            "6"=>"[Wtyu]",
            "7"=>"[Re]",
            "8"=>"[O5tyus]",
            "9"=>"[Ir]",
            "0"=>"[P77t]",
            "!"=>"[A4tyu]",
            "£"=>"[Hg]",
            "$"=>"[Tl]",
            "%"=>"[P7b]",
            "^"=>"[B4i]",
            "&"=>"[P77o]",
            "*"=>"[A4t]",
            "("=>"[Rn]",
            ")"=>"[Fr]",
            "_"=>"[R7a]",
            "-"=>"[R4f]",
            "="=>"[Db]",
            "+"=>"[Sg]",
            "`"=>"[4Bh]",
            "¬"=>"[Hs]",
            ""=>"[Mt]",
            ","=>"[Ds]",
            "<"=>"[Rg]",
            "."=>"[CfRsn7]",
            ">"=>"[Uut]",
            "/"=>"[Fdg65l]",
            "?"=>"[Uup]",
            ";"=>"[Lv]",
            ":"=>"[Uus]",
            "'"=>"[Uuo]",
            "@"=>"[La]",
            "#"=>"[Ce]",
            "~"=>"[PlQxCr]",
            "["=>"[Nrg9d]",
            "]"=>"[47Pm]",
            "{"=>"[Smr]",
            "}"=>"[Eu]",
            "."=>"Gd",
        ];
    }
}
?>