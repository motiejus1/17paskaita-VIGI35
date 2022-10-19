<?php


// 1. Prisijungimas prie duomenu bazes
// 2. Veiksmai is duomenu bazes
// 3. Atsijungimas nuo duomenu bazes

//kai tik atliekam veiksmus su duomenu baze, siuos veiksmus atlike - uzdarome prisijungima
//naudojam duombaze tik tuo atveju kai reikia


//atvaizdavimo koda - prisijungimas prie duomenu bazes?



//PDO - PHP Data Objects

class DatabaseManager {
    //autentifikacijos kintamieji
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'klientu_duombaze';

    protected $conn;

    // konstruktorius yra metodas, kuris pasileidzia iskart sukurus objekta
    public function __construct() {
        // 1. Prisijungimas prie duomenu bazes
        //bet kokiu klaidu

        //reikia utf8 koduotes !!!!!!!!!!

        try { //koki koda mes norime ismeginti
            //ivyko kazkokia klaida
            //vykdomas kodas, jei klaida neivyko
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            echo "Prisijungta prie duomenu bazes";
        } catch (PDOException $e) {
            //$e - objektas, kuris saugo klaidos informacija
            echo "Klaida: " . $e->getMessage();
        }
    }

    // 2. Veiksmai is duomenu bazes

    public function select($table) {
        // SELECT * FROM `kompanijos` WHERE 1
        $sql = "SELECT * FROM `$table` WHERE 1";
        try {

            //paruosti prisijungima uzklausos vykdymui
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //kad rodytu klaidas
            //paruosti uzklausa vykdymui
            $stmt = $this->conn->prepare($sql);
            //vykdyti uzklausa
            $stmt->execute();

            //rezultato pasidejimas

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // lenteles pasirnkti duomenys yra paverciami i asociatyvu masyva
            $result = $stmt->fetchAll();

            return $result; //visas kompanijas kaip asociatyvu masyva

        } catch (PDOException $e) {
            echo "Klaida: " . $e->getMessage();
        }
    }

    public function insert($table, $cols, $values) {    

        // $cols - kaip masyva, kuriame yra surasomi stulpeliu pavadinimai
        // ["pavadinimas", "aprasymas"] -> pavadinimas,aprasymas
        // $values - kaip masyva, kuriame yra surasomi stulpeliu reiksmes
        //  ["'kompanija'", "'aprasymas'"] - > 'kompanija','aprasymas'


        $cols = implode(',', $cols); // pavadinimas,aprasymas
        $values = implode(',', $values); // pavadinimas',aprasymas'

        //lentele gali tureti skirtingus stuleplius, skirtingas ivedamas reiksmes
        $sql = "INSERT INTO `$table`($cols) VALUES ($values)";
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //exec komanda naudojama kai uzklausa negrazina rezulatu
            $this->conn->exec($sql);

            echo "irasas   sekmingai pridetas";
        }
        catch(PDOException $e) {
            echo "Klaida: " . $e->getMessage();
        }
    }

    public function update() {
        echo "UPDATE sekmingas";
    }

    public function delete() {
        echo "DELETE sekmingas";
    }

    //kai objektas baigia savo veiksmus, tai jis yra sunaikinamas

    //destruktorius - metodas, kuris pasileidzia kai objektas sunaikinamas/istrinamas

    public function __destruct() {
        // 3. Atsijungimas nuo duomenu bazes
        $this->conn = null; //prisijungimas = null,
        echo "  Atsijungta nuo duomenu bazes";
    }
}

$databaseManager = new DatabaseManager();
echo "<br>";
$klientai = $databaseManager->select("klientai");
$kompanijos=$databaseManager->select("kompanijos");


var_dump($klientai);
var_dump($kompanijos);
 echo "<br>";
 $databaseManager->insert("klientai", ["vardas", "pavarde","miestas", "kompanijosID"], ["'Jonas'", "'Jonaitis'", "'Vilnius'", 1]);
// echo "<br>";
// $databaseManager->update();
// echo "<br>";
// $databaseManager->delete();
// echo "<br>";

//$databaseManager - istrinamas
//atsijungimas nuo duomenu bazes
echo "<br>";
