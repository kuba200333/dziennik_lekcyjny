<?php
session_start();
if (!isset($_SESSION['zalogowany'])){
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodawanie ocen z przedmiotu</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
<div class="kontener">
        <h4 class="inside">Dodaj ocenę </h4>
        <table>
        <form action="" method='post'>
        <tr><td class='3' colspan="2"></td></tr>
        <?php
            $login=$_SESSION['login'];

            require "connect.php";

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    
            if($_SESSION['admin'] !=1){
                
                $zapytanie11="SELECT DISTINCT k.skrot_klasy as klasa FROM nauczanie n inner join klasy k on n.id_klasy=k.id_klasy inner join przedmioty p on n.id_przedmiot=p.id_przedmiotu inner join nauczyciele na on n.id_nauczyciel=na.id_nauczyciela where na.login='$login' order by klasa asc;";
                $wyslij11=mysqli_query($polaczenie,$zapytanie11);  

                if ($wyslij11->num_rows>0){
                echo "<tr><td class='1'>klasa:</td> <td class='2'>";
                    if(empty($_POST['klasy'])){
                    echo"<select name='klasy' onchange='this.form.submit()' required>";
                    echo "<option value=''</option>";
                    while($row11=mysqli_fetch_array($wyslij11)){
                        echo "<option>".$row11['klasa']."</option>";
                    }
                    echo "</select>";
                    } else{
                        echo $_POST['klasy'];
                    }
                }else{
                    echo "Nie uczysz w żadnej klasie";
                }
            }

            $login=$_SESSION['login'];
            if($_SESSION['admin'] ==1){
                $zapytanie110="SELECT DISTINCT k.skrot_klasy as klasa FROM nauczanie n inner join klasy k on n.id_klasy=k.id_klasy inner join przedmioty p on n.id_przedmiot=p.id_przedmiotu inner join nauczyciele na on n.id_nauczyciel=na.id_nauczyciela order by klasa asc;";
                $wyslij110=mysqli_query($polaczenie,$zapytanie110);  

                echo "<tr><td class='1'>klasa:</td> <td class='2'>";
                if(empty($_POST['klasy'])){
                    echo"<select name='klasy' onchange='this.form.submit()' required>";
                    echo "<option value=''</option>";
                    while($row110=mysqli_fetch_array($wyslij110)){
                        echo "<option>".$row110['klasa']."</option>";
                    }
                    echo "</select>";
                    } else{
                        echo $_POST['klasy'];
                    }
            }

            if(empty($_POST['klasy'])){
            echo <<<END
            <tr class='inside'><td class='3' colspan='2'><input type='submit' value='Zamknij' name='zamknij' onclick="window.open('', '_self', ''); window.close();"></td></tr>
            END;
            }
            mysqli_close($polaczenie);
        ?>    
           
        

        <br>

        <?php
            if(!empty($_POST['klasy'])){

            require "connect.php";

            $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
            $skrot_klasy=@$_POST['klasy'];

    
            $zapytanie13="SELECT id_klasy from klasy where skrot_klasy='".$skrot_klasy."';";
            $wyslij13=mysqli_query($polaczenie,$zapytanie13);  
        
        
            while($row13=mysqli_fetch_array($wyslij13)){
                $id_klasy=$row13['id_klasy'];
            }
        

            $zapytanie10="SELECT concat(nazwisko_ucznia, ' ', imie_ucznia) as uczen FROM uczniowie where id_klasy=$id_klasy UNION SELECT concat(nazwisko_ucznia, ' ', imie_ucznia) as uczen FROM wirtualne_klasy where id_klasy=$id_klasy order by uczen asc;";

            $wyslij10=mysqli_query($polaczenie,$zapytanie10);  
            
            echo "<tr><td class='1'>uczeń:</td> <td class='2'><select name='uczen' required> ";
            echo "<option value=''</option>";
            while($row10=mysqli_fetch_array($wyslij10)){
                echo "<option>".$row10[0]."</option>";
            }
            echo "</select></td></tr>";

            $login=$_SESSION['login'];
            if($_SESSION['admin'] !=1){
                $zapytanie="SELECT DISTINCT p.nazwa_przedmiotu as przedmiot FROM nauczanie n inner join klasy k on n.id_klasy=k.id_klasy inner join przedmioty p on n.id_przedmiot=p.id_przedmiotu 
                inner join nauczyciele na on n.id_nauczyciel=na.id_nauczyciela where k.skrot_klasy='$skrot_klasy' and na.login='$login' order by p.nazwa_przedmiotu asc;";
                $wyslij=mysqli_query($polaczenie,$zapytanie);
        
                echo "<tr><td class='1'>przedmiot:</td> <td class='2'>";
                if ($wyslij->num_rows>0){
                echo"<select name='przedmiot' required>";
                echo "<option value=''</option>";
                while($row=mysqli_fetch_array($wyslij)){
                    echo "<option>".$row[0]."</option>";
                }
                echo "</select></td></tr>";
                } else{
                    $nie_uczy=1;
                    echo "Nie uczysz w tej klasie";
                }
            }
            if($_SESSION['admin'] ==1){
                $zapytanie="SELECT DISTINCT p.nazwa_przedmiotu as przedmiot FROM nauczanie n inner join klasy k on n.id_klasy=k.id_klasy inner join przedmioty p on n.id_przedmiot=p.id_przedmiotu 
                inner join nauczyciele na on n.id_nauczyciel=na.id_nauczyciela where k.skrot_klasy='$skrot_klasy' order by p.nazwa_przedmiotu asc;";
                $wyslij=mysqli_query($polaczenie,$zapytanie);
            
                echo "<tr><td class='1'>przedmiot:</td> <td class='2'>";
                if ($wyslij->num_rows>0){
                echo"<select name='przedmiot' required>";
                echo "<option value=''</option>";
                while($row=mysqli_fetch_array($wyslij)){
                    echo "<option>".$row[0]."</option>";
                }
                echo "</select></td></tr>";
                } else{
                    echo "Nie uczysz w tej klasie";
                }
            }

            $zapytanie5="SELECT nazwa_kategorii FROM `kategorie_ocen` where id_kategorii not in (9,10) order by nazwa_kategorii asc;";
            
            $wyslij5=mysqli_query($polaczenie,$zapytanie5);
            
            echo "<tr><td class='1'>kategoria:</td> <td class='2'><select name='kategoria' required>";
            echo "<option value=''</option>";
            while($row5=mysqli_fetch_array($wyslij5)){
                echo "<option>".$row5['nazwa_kategorii']."</option>";
            }
            echo "</select></td></tr>";        
            
            echo <<<END
            <tr><td class='1'>ocena</td><td class='2'>
            <input list='oceny' name='ocena'>
            <datalist id='oceny' required>
                <option>1</option>
                <option>1+</option>
                <option>2-</option>
                <option>2</option>
                <option>2+</option>
                <option>3-</option>
                <option>3</option>
                <option>3+</option>
                <option>4-</option>
                <option>4</option>
                <option>4+</option>
                <option>5-</option>
                <option selected>5</option>
                <option>5+</option>
                <option>6-</option>
                <option>6</option>
                <option>nk</option>
                <option>zw</option>
                <option>+</option>
                <option>-</option>
                <option>np</option>
                <option>nu</option>
            </datalist>
            
            </td></tr>


            
            END;
            $zapytanie1="SELECT id_nauczyciela, concat(nazwisko, ' ', imie) as nauczyciel FROM `nauczyciele` where login='$login';";
            $wyslij1=mysqli_query($polaczenie,$zapytanie1);
    
            $login=$_SESSION['login'];
            echo "<tr><td class='1'>nauczyciel:</td> <td class='2'>";
            while($row1=mysqli_fetch_array($wyslij1)){
                echo $row1['nauczyciel']."<input type='hidden' value='$row1[1]' name='nauczyciel'>";  
            }
            echo "</tr>";

            $d=mktime();
            $date=date("Y-m-d", $d);

            echo "<tr><td class='1'>data</td><td class='2'><input type='date' value='$date' name='data' required></td></tr>";

            echo "<tr><td class='1'>komentarz</td><td class='2'><textarea name='komentarz'></textarea></td></tr>";
            
            if(isset($nie_uczy)){
                echo <<<END
                <tr class='inside'><td class='3' colspan='2'><input type='submit' value='Zamknij' name='zamknij' onclick="window.open('', '_self', ''); window.close();"></td></tr>
                END;
            }
            if(!isset($nie_uczy)){
                echo <<<END
                <tr class='inside'><td class='3' colspan='2'>
                <input value='Dodaj' type='submit' name='wysylacz'>
                <input type='submit' value='Zamknij' name='zamknij' onclick="window.open('', '_self', ''); window.close();"></td></tr>
                END;
            }

            mysqli_close($polaczenie);
        }



    echo "</table></form>";


    
    if (isset($_POST['wysylacz'])) {
        require "connect.php";

        $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        $data=$_POST['data'];  
        $przedmiot=$_POST['przedmiot'];
        $ocena=$_POST['ocena'];  
        $uczen=$_POST['uczen'];
        $komentarz=$_POST['komentarz'];
        
        $kategoria=$_POST['kategoria'];
        $nauczyciel=$_POST['nauczyciel'];  
        $zapytanie2='SELECT id_przedmiotu from przedmioty where nazwa_przedmiotu="'.$przedmiot.'";';
        $wyslij2=mysqli_query($polaczenie,$zapytanie2);

        if($ocena=="1+"){
            $ocena=1.5;
        }else if($ocena=="2-"){
            $ocena=1.75;
        }else if($ocena=="2+"){
            $ocena=2.5;
        }else if($ocena=="3-"){
            $ocena=2.75;
        }else if($ocena=="3+"){
            $ocena=3.5;
        }else if($ocena=="4-"){
            $ocena=3.75;
        }else if($ocena=="4+"){
            $ocena=4.5;
        }else if($ocena=="5-"){
            $ocena=4.75;
        }else if($ocena=="5+"){
            $ocena=5.5;
        }else if($ocena=="6-"){
            $ocena=5.75;
        }else if($ocena=="+"){
            $ocena=0.5;
        }else if($ocena=="-"){
            $ocena=0.25;
        }else if($ocena=="nk"){
            $ocena=0.01;
        }else if($ocena=="zw"){
            $ocena=0.02;
        }else if($ocena=="np"){
            $ocena=0.03;
        }else if($ocena=="nu"){
            $ocena=0.04;
        }

        while($row2=mysqli_fetch_array($wyslij2)){
            $id_przedmiotu=$row2['id_przedmiotu'];
        }
    
        $zapytanie4="SELECT id_nauczyciela from `nauczyciele` where concat(nazwisko, ' ', imie)='$nauczyciel';";

        $wyslij4=mysqli_query($polaczenie,$zapytanie4);
    

        while($row4=mysqli_fetch_array($wyslij4)){
            $id_nauczyciela=$row4['id_nauczyciela'];
        }

        $zapytanie7="SELECT id_kategorii from kategorie_ocen where nazwa_kategorii='$kategoria';";

        $wyslij7=mysqli_query($polaczenie,$zapytanie7);
    

        while($row7=mysqli_fetch_array($wyslij7)){
            $id_kategorii=$row7['id_kategorii'];
        }

                
        $zapytanie9="SELECT id_ucznia FROM uczniowie where concat(nazwisko_ucznia, ' ', imie_ucznia)='$uczen';";

        $wyslij9=mysqli_query($polaczenie,$zapytanie9);  

        while($row9=mysqli_fetch_array($wyslij9)){
            $id_ucznia=$row9['id_ucznia'];
        }


        $zapytanie12="SELECT waga from kategorie_ocen where id_kategorii=$id_kategorii;";
        $wyslij12=mysqli_query($polaczenie,$zapytanie12);  

        while($row12=mysqli_fetch_array($wyslij12)){
            $waga=$row12['waga'];
        }

        $zapytanie20="SELECT id from semestry where '$data' between od and do;";
        $wyslij20=mysqli_query($polaczenie,$zapytanie20);
        while($row20=mysqli_fetch_array($wyslij20)){
            $semestr=$row20[0];
        }

        if($ocena<1 and $ocena>6){
            $nie_licz=1;
        }else{
            $nie_licz=0;
        }
        
        $zapytanie3="INSERT INTO oceny (id_oceny, id_przedmiotu, ocena, data, id_nauczyciela, id_kategorii, id_ucznia, semestr,komentarz, waga, nie_licz) VALUES (null,".$id_przedmiotu.",$ocena,'$data',$id_nauczyciela, $id_kategorii, $id_ucznia, $semestr, '$komentarz', $waga, $nie_licz);";

        $wyslij3=mysqli_query($polaczenie,$zapytanie3);

        mysqli_close($polaczenie);
    }
    
    ?>
    </div>
</body>
</html>