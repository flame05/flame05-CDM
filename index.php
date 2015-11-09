<?php
session_start();
$db = new mysqli("localhost", "cdm", "Ktts4%57", "flame-cdm");

class user
{
    function __construct()
    {
        $this->online = false;
        $this->offline = true;
        $this->l = 0;
        if(isset($_SESSION['id']))
        {
            $this->id = $_SESSION['id'];
            $this->l = $_SESSION['livello'];
            $this->user = $_SESSION['user'];
            $this->online = true;
            $this->offline = false;
        }
    }

    function login($usern,$pass)
    {
        global $db;
        $sql = "SELECT id,livello,username FROM utenti WHERE username = '".$db->escape_string($usern)."' AND password = '".$db->escape_string($pass)."' LIMIT 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['livello'] = $row['livello'];
            $_SESSION['user'] = $row['username'];
        return true;
        }
    return false;
    }

    function logout()
    {
        $_SESSION = array();
        session_destroy();
        return true;
    }

}

class zutente
{
    function __construct($id,$user,$livello,$nome)
    {
        $this->id = $id;
        $this->user = $user;
        $this->livello = $livello;
        $this->nome = $nome;
    }
}

class zprocesso
{
    function __construct($id,$nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }
}

class zmodello
{
    function __construct($id,$id_pro,$nome)
    {
        $this->id = $id;
        $this->id_pro = $id_pro;
        $this->nome = $nome;
    }
}

class zparte
{
    function __construct($id,$id_modello,$nome,$ordine)
    {
        $this->id = $id;
        $this->id_modello = $id_modello;
        $this->nome = $nome;
        $this->ordine = $ordine;
    }
}

class zlavori
{
    function __construct($id,$id_mamma,$nome,$colore,$ordine)
    {
        $this->id = $id;
        $this->id_modello = $id_modello;
        $this->nome = $nome;
        $this->colore = $colore;
        $this->ordine = $ordine;
    }
}

class zlavoro
{
    function __construct($id,$id_mamma,$nome,$ordine)
    {
        $this->id = $id;
        $this->id_modello = $id_modello;
        $this->nome = $nome;
        $this->ordine = $ordine;
    }
}

class utente
{
    function crea($user,$pass,$livello,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO utenti (username,password,livello,nome) VALUES ('".$db->escape_string($user)."','".$db->escape_string($pass)."','".$livello."','".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE utenti SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function aggiorna($id,$pass,$livello,$nome,$user,$zen)
    {
        global $db;
        $sql = "UPDATE utenti SET password = '".$db->escape_string($pass)."',livello = '".$livello."',username = '".$db->escape_string($user)."',nome = '".$db->escape_string($nome)."',zen = '".$zen."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

    function getUsers()
    {
        global $db;
        $utenti = array();
        $sql = "SELECT * FROM utenti WHERE zen = 1 AND livello <= ".$_SESSION['livello']."";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
            $utenti[] = new zutente($row['id'],$row['username'],$row['livello'],$row['nome']);
        }
    return $utenti;
    }

    function loadUser($id)
    {
    global $db;
        $sql = "SELECT * FROM utenti WHERE id = ".$id." AND zen = 1 AND livello <= ".$_SESSION['livello']." LIMIT 1";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
            $zu = array();
            $zu['id'] = $row['id'];
            $zu['username'] = $row['username'];
            $zu['password'] = $row['password'];
            $zu['livello'] = $row['livello'];
            $zu['nome'] = $row['nome'];
            $zu['zen'] = $row['zen'];
        }
    return $zu;
    }
}

class processo
{
    function crea($nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO processo (nome) VALUES ('".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getProcessi()
    {
        global $db;
        $processi = array();
        $sql = "SELECT * FROM processo WHERE zen = 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $processi[] = new zprocesso($row['id'],$row['nome']);
            }
        return $processi;
        }
    return $processi;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE processo SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            $this->created = true;
    }
}

class modello
{
    function crea($id_pro,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO modello (id_pro,nome) VALUES ('".$id_pro."','".$db->escape_string($nome)."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getModelli($id_pro)
    {
        global $db;
        $modelli = array();
        $sql = "SELECT * FROM modello WHERE id_pro = ".$id_pro." AND zen = 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $modelli[] = new zmodello($row['id'],$row['id_pro'],$row['nome']);
            }
        return $modelli;
        }
    return $modelli;
    }

    function elimina($id)
    {
        global $db;
        $sql = "UPDATE modello SET zen = 0 WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            $this->created = true;
    }
}


class parte
{
    function crea($id_modello,$nome)
    {
        global $db;
        $this->created = false;

        $sql = "SELECT count(*) AS totale FROM parte WHERE id_modello = ".$id_modello." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;


        $sql = "INSERT INTO parte (id_modello,nome,ordine) VALUES ('".$id_modello."','".$db->escape_string($nome)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getParti($id_modello)
    {
        global $db;
        $parti = array();
        $sql = "SELECT * FROM parte WHERE id_modello = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $parti[] = new zparte($row['id'],$row['id_modello'],$row['nome'],$row['ordine']);
            }
        return $parti;
        }
    return $parti;
    }

    function aggiorna($id,$nome,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }
}



class logp
{
    function crea($id_articolo,$id_lavoro)
    {
        global $db;
        $this->created = false;
        $sql = "INSERT INTO logp (id_articolo,id_lavoro,tempo,id_user) VALUES ('".$id_articolo."','".$id_lavoro."','".time()."','".$_SESSION['id']."')";
        if($db->query($sql))
            $this->created = true;
    }
}

class lavori
{
    function crea($id_mamma,$nome,$colore)
    {
        global $db;
        $this->created = false;
        $sql = "SELECT count(*) AS totale FROM lavori WHERE id_mamma = ".$id_mamma." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;

        $sql = "INSERT INTO lavoro (id_mamma,nome,colore,ordine) VALUES ('".$id_mamma."','".$db->escape_string($nome)."','".$db->escape_string($colore)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getLavori($id_mamma)
    {
        global $db;
        $lavori = array();
        $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_mamma." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $lavori[] = new zlavori($row['id'],$row['id_mamma'],$row['nome'],$row['colore'],$row['ordine']);
            }
        return $lavori;
        }
    return $lavori;
    }

    function aggiorna($id,$nome,$colore,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."',colore = '".$db->escape_string($colore)."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }
}


class lavoro
{
    function crea($id_mamma,$nome)
    {
        global $db;
        $this->created = false;
        $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
        $result = $db->query($sql);
        $row = $result->fetch_assoc();
        $ordine = $row['totale'] + 1;

        $sql = "INSERT INTO lavoro (id_mamma,nome,ordine) VALUES ('".$id_mamma."','".$db->escape_string($nome)."','".$ordine."')";
        if($db->query($sql))
            $this->created = true;
    }

    function getLavoro($id_mamma)
    {
        global $db;
        $lavoro = array();
        $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1 ORDER BY ordine ASC";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $lavoro[] = new zlavoro($row['id'],$row['id_mamma'],$row['nome'],$row['ordine']);
            }
        return $lavoro;
        }
    return $lavoro;
    }

    function aggiorna($id,$nome,$ordine)
    {
    global $db;
        $sql = "UPDATE parte SET nome = '".$db->escape_string($nome)."',ordine = '".$ordine."' WHERE id = ".$id." LIMIT 1";
        if($db->query($sql))
            return true;
    return false;
    }

}


class articolo
{
    function crea($id_modello,$id_parte,$pos_madre,$posizione,$nome,$colore)
    {
        global $db;
        $this->created = false;

        $sql = "INSERT INTO articolo (id_modello,id_parte,posizione,pos_madre,nome,colore,id_user,tempo) VALUES ('".$id_modello."','".$id_parte."','".$posizione."','".$pos_madre."','".$db->escape_string($nome)."','".$colore."','".$_SESSION['id']."','".time()."')";
        if($db->query($sql))
            $this->created = true;
    }

    function sposta($id,$indietro = false)
    {
        global $db;

        $sql = "SELECT id_modello,posizione,pos_madre FROM articolo WHERE id = ".$id." AND zen = 1 LIMIT 1";
        $result = $db->query($sql);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $posizione = $row['posizione'];
            $pos_madre = $row['pos_madre'];
            $id_modello = $row['id_modello'];

            /* AL CONTRARIO */
            if($indietro)
            {
                if($pos_madre == 1 && $posizione == 1)
                    return false;
                if($posizione > 1)
                {
                    $np = $posizione-1;
                    $sql = "UPDATE articolo SET posizione = ".$np." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                }
                else
                {
                    $np = $pos_madre-1;
                    $sql = "SELECT id FROM lavori WHERE ordine = ".$np." AND id_mamma = ".$id_modello." LIMIT 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $id_new = $row['id'];
                    $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_new." AND zen = 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $totale = $row['totale'];
                    $sql = "UPDATE articolo SET posizione = ".$totale.",pos_madre = ".$np." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                return 'error';
                }
            }


            /* IN AVANTI */

            $sql = "SELECT id FROM lavori WHERE ordine = ".$pos_madre." AND zen = 1 AND id_mamma = ".$id_modello." LIMIT 1";
            $result = $db->query($sql);
            if($result->num_rows > 0)
            {
                $row = $result ->fetch_assoc();
                $id_mamma = $row['id'];
                $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
                $result = $db->query($sql);
                $row = $result->fetch_assoc();
                $totale = $row['totale'];

                if($totale == $posizione)
                {

                    $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$id_mamma." AND zen = 1";
                    $result = $db->query($sql);
                    $row = $result->fetch_assoc();
                    $totale = $row['totale'];
                    if($totale == $pos_madre)
                    {
                        $sql = "UPDATE articolo SET finito = 1 WHERE id = ".$id." LIMIT 1";
                        $db->query($sql);
                    return 'finito';
                    }
                    else
                    {
                        $nuovo_ordine_m = $pos_madre + 1;
                        $sql = "UPDATE articolo SET posizione = 1,pos_madre = ".$nuovo_ordine_m." WHERE id = ".$id." LIMIT 1";
                        if($db->query($sql))
                            return true;
                    }
                }
                else
                {
                    $nuovo_ordine = $posizione + 1;
                    $sql = "UPDATE articolo SET posizione = ".$nuovo_ordine." WHERE id = ".$id." LIMIT 1";
                    if($db->query($sql))
                        return true;
                }
            }
            return 'error';
        }
    return false;
    }
}

$admin = array();
$admin[1] = 'Operatore';
$admin[2] = 'Capo Operatore';
$admin[5] = 'Logistica';
$admin[9] = 'Admin';
$cdm_mess = false;
$user = new user();
$pagina = 'home';
if(!empty($_GET['page']))
{
    $pagina = $_GET['page'];
    if($pagina == 'login')
    {
        if(!empty($_POST['cdm-user']))
        {
            if($user->login($_POST['cdm-user'],$_POST['cdm-pass']))
            {
                header("Location: index.php");
            exit;
            }

        }
    }
    if($pagina == 'logout')
    {
        $user->logout();
        header("Location: index.php");
    exit;
    }
}

if(isset($_GET['update'])){
    $utente = new utente();
    $arr = $utente->loadUser($_GET['id']);
    echo json_encode($arr);
    exit;
}

if(isset($_GET['sposta'])){
    $articolo = new articolo();
    $articolo->sposta($_GET['sposta']);
header("Location: index.php");
    exit;
}

if(isset($_GET['addArticolo'])){
    $articolo = new articolo();
    $articolo->crea($_POST['add_id_modello'],$_POST['add_id_parte'],$_POST['add_pos_madre'],$_POST['add_posizione'],$_POST['add_nome'],$_POST['add_colore']);
header("Location: index.php");
    exit;
}

?>




<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Applicazione per catena di montaggio">
<meta name="author" content="Daniel Bernasconi">

<title>Demo App</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
</head>


<body>
<?php
if($pagina == 'admin'){
 ?>
<div class="modal fade" id="modUtente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modifica utente</h4>
      </div>
      <div class="modal-body">
         <form action="index.php?page=admin&subpage=users&method=mod" id="modForm" method="POST">
         <input type="text" id="mod_id" name="mod_id" style="visibility:hidden;">
         <input type="text" id="mod_zen" name="mod_zen" value="1" style="visibility:hidden;">
      <div class="form-group">
        <label>Nome</label>
        <input type="text" id="mod_nome" name="mod_nome" class="form-control">
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" id="mod_username" name="mod_username" class="form-control">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="text" id="mod_password" name="mod_password" class="form-control">
      </div>
      <div class="form-group">
        <label>Livello</label>
        <select id="mod_livello" name="mod_livello" class="form-control">
            <?php
            foreach($admin as $n => $nn)
                echo '<option value="'.$n.'">'.$nn.'</option>';
            ?>
        </select>
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="modErase">Elimina utente</button>
        <button type="button" class="btn btn-primary" id="modSubmit">Salva modifiche</button>
      </div>
    </div>
  </div>
</div>

 <?php } ?>

 <div class="modal fade" id="addArticolo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Aggiungi Articolo</h4>
      </div>
      <div class="modal-body">
         <form action="index.php?addArticolo" id="addForm" method="POST">
         <input type="text" id="add_pos_madre" name="add_pos_madre" style="visibility:hidden;">
         <input type="text" id="add_id_modello" name="add_id_modello" style="visibility:hidden;">
         <input type="text" id="add_posizione" name="add_posizione" style="visibility:hidden;">
         <input type="text" id="add_id_parte" name="add_id_parte" style="visibility:hidden;">
      <div class="form-group">
        <label>Nome</label>
        <input type="text" id="add_nome" name="add_nome" class="form-control">
      </div>
      <div class="form-group">
        <label>Colore</label>
        <input type="text" id="add_colore" name="add_colore" value="btn-primary" class="form-control">
      </div>
    </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="addArticle">Aggiungi</button>
      </div>
    </div>
  </div>
</div>



    <nav class="navbar navbar-default navbar-fixed-top" style="width:100%;">
      <div class="container" style="width:100%;">
        <div class="navbar-header">
          <a class="navbar-brand" style="background-color: #2b669a;color:#FFF; " href="javascript:void(0);">CDM</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">


            <?php if($user->online) { ?><li<?php if($pagina == 'home'){ ?> class="active"<?php } ?>><a href="index.php">Catena di Montaggio</a></li><?php } ?>
            <?php if($user->l > 4) { ?><li<?php if($pagina == 'admin'){ ?> class="active"<?php } ?>><a href="index.php?page=admin">Amministrazione</a></li><?php } ?>
            <?php if($user->online) { ?><li><a href="index.php?page=logout">Logout</a></li><?php } ?>
            <?php if($user->offline){ ?><li><a href="javascript:void(0);">Login</a></li> <?php } ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    
    <?php if($user->offline){ ?>
    <div class="container" style="margin-top: 100px;">
         <div class="col-md-6"><h1>Login</h1>
        <form action="index.php?page=login" method="POST">
        <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="cdm-user">
        </div>
        <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="cdm-pass">
        </div>
        <button type="submit" class="btn btn-default">Accedi</button>
        </form>
        </div>
    </div>
    <?php } else{

        switch($pagina)
        {
            case 'admin':
            if(!empty($_GET['subpage']))
            {
                switch($_GET['subpage'])
                {
                    case 'cdm':

                    break;
                    case 'users':
                        if(!empty($_GET['method']))
                        {
                            switch($_GET['method'])
                            {
                                case 'add':
                                    $utente = new utente();
                                    $utente->crea($_POST['username'],$_POST['password'],$_POST['livello'],$_POST['nome']);
                                    if($utente->created)
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente creato con successo!</strong></div>';
                                break;
                                case 'mod':
                                    $utente = new utente();
                                    $utente->aggiorna($_POST['mod_id'],$_POST['mod_password'],$_POST['mod_livello'],$_POST['mod_nome'],$_POST['mod_username'],$_POST['mod_zen']);
                                    if($_POST['mod_zen'] == 0)
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente eliminato con successo!</strong></div>';
                                    else
                                        $cdm_mess = '<div class="alert alert-success alert-dismissible fade in" role="alert">
                                                     <button type="button" class="close" data-dismiss="alert" aria-label="Chiudi"><span aria-hidden="true">×</span></button>
                                                     <strong>Utente modificato con successo!</strong></div>';
                                break;
                            }
                        }

                        ?>

                        <div class="container" style="margin-top: 100px;">
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-3">&nbsp;</div>
                                <div class="col-md-6">
                                <?php
                                if($cdm_mess) echo $cdm_mess;
                                 ?>


                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title list-group-item active">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          Aggiungi utente
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        <form action="index.php?page=admin&subpage=users&method=add" method="POST">
                                          <div class="form-group">
                                            <label>Nome</label>
                                            <input type="text" name="nome" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Password</label>
                                            <input type="text" name="password" class="form-control">
                                          </div>
                                          <div class="form-group">
                                            <label>Livello</label>
                                            <select name="livello" class="form-control">
                                                <?php
                                                foreach($admin as $n => $nn)
                                                    echo '<option value="'.$n.'">'.$nn.'</option>';
                                                ?>
                                            </select>
                                          </div>
                                          <button type="submit" class="btn btn-default">Submit</button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title list-group-item active">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Lista utenti
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        <div class="table-responsive">
                                          <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                 <th>ID</th>
                                                 <th>Nome</th>
                                                 <th>Username</th>
                                                 <th>Livello</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $utente = new utente();
                                                $utenti = $utente->getUsers();
                                                foreach($utenti as $key => $outente)
                                                {

                                                    echo '<tr><td>'.$outente->id.'</td><td>'.$outente->nome.'</td><td><a href="javascript:void(0);" class="cmdMod" data-id="'.$outente->id.'">'.$outente->user.'</a></td><td>'.$admin[$outente->livello].'</td></tr>';
                                                }
                                                
                                            ?>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                               </div>
                            </div>               
                        </div>
                        <?php
                    break;
                }
            }
            else
            {
            ?>
                <div class="container" style="margin-top: 100px;">
                    <div class="row">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4 list-group-item active">Aggiungi utente</div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <a href="index.php?page=admin&subpage=users">Gestione utenti</a></div>
                    </div>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <a href="index.php?page=admin&subpage=cdm">Gestione CDM</a></div>
                    </div>                    
                </div>
            <?php
            }
            break;
            default:
    ?>
    <div class="container" style="margin-top: 100px;width:100%;">
    <?php
        $sql = "SELECT * FROM processo WHERE zen = 1";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
        {
    ?>
        <div class="row">
        <div class="col-md-12 list-group-item active" style="text-align: center;"><?php echo $row['nome']; ?></div>
        </div>
    <?php 
        $sql_master = "SELECT * FROM modello WHERE id_pro = ".$row['id']." AND zen = 1";
        $result = $db->query($sql_master);
        while($row = $result->fetch_assoc())
        {
            $nome_modello = $row['nome'];
            $id_modello = $row['id'];
            $sql = "SELECT * FROM lavori WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
            $result = $db->query($sql);
            $totale_lavori = $result->num_rows;
            ?>
            <div class="row">
                <div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:15px;border-radius: 6px; background-color:#2F71AA;color:#FFF;"><?php echo $nome_modello; ?></div>
            <?php
            while($row = $result->fetch_assoc())
            {

                $sql = "SELECT count(*) AS totale FROM lavoro WHERE id_mamma = ".$row['id']."";
                $res = $db->query($sql);
                $raw = $res->fetch_assoc();
                $tot = $raw['totale'];
                ?>
                <div class="col-md-<?php echo $tot; ?>" style="padding: 10px 0px 8px 15px;margin-top:15px;border-radius: 6px;color:#FFF; background-color:<?php echo $row['colore']; ?>;"><?php echo $row['nome']; ?></div>
                <?php
            }
            ?>
            </div>
            <?php
            $result = $db->query($sql_master);
            while($row = $result->fetch_assoc())
            {
                $parte = new parte();
                $id_modello = $row['id'];
                $parti = $parte->getParti($row['id']);
                $i = 0;
                foreach($parti as $n => $part)
                {
                    if($i == 0)
                    {
                    ?>
                    <div class="row"><div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;">&nbsp;</div>
                    <?php
                        $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
                        $result = $db->query($sql);
                        while($row = $result->fetch_assoc())
                        {
                            $colore = $row['colore'];
                            $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
                            $result2 = $db->query($sql);
                            while($row2 = $result2->fetch_assoc())
                            {
                            ?>
                                <div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;color:#FFF;border-radius: 6px; background-color:<?php echo $colore; ?>"><?php echo $row2['nome']; ?></div>
                            <?php
                            }
                        }
                        ?>
                            </div>
                            <?php
                    $i = 1;
                    }
                    ?>
                    <div class="row"><div class="col-md-1" style="padding: 10px 0px 8px 15px;margin-top:5px;"><?php echo $part->nome; ?></div>
                    <?php 
                    $sql = "SELECT * FROM lavori WHERE id_mamma = ".$id_modello." AND zen = 1 ORDER BY ordine ASC";
                    $result = $db->query($sql);
                    while($row = $result->fetch_assoc())
                    {
                        $colore = $row['colore'];
                        $sql = "SELECT * FROM lavoro WHERE id_mamma = ".$row['id']." AND zen = 1 ORDER BY ordine ASC";
                        $result2 = $db->query($sql);
                        while($row2 = $result2->fetch_assoc())
                        {
                            ?>
                            <div class="col-md-1">
                            <?php
                            $sql = "SELECT * FROM articolo WHERE id_modello = ".$id_modello." AND id_parte = ".$part->id." AND zen = 1 AND finito = 0 AND pos_madre = ".$row['ordine']." AND posizione = ".$row2['ordine']."";
                            $result3 = $db->query($sql);
                            while($row3 = $result3->fetch_assoc())
                            {
                                ?>
                                <a href="index.php?sposta=<?php echo $row3['id']; ?>"><button type="button" class="btn btn-sm spostami <?php echo $row3['colore']; ?>" data-id="<?php echo $row3['id']; ?>" style="margin-top:5px;"><?php echo $row3['nome']; ?></button></a>
                                <?php
                            }
                            if($user->l > 4) { ?> <a class="addArti" href="javascript:void(0);" data-posizione="<?php echo $row2['ordine']; ?>" data-id-modello="<?php echo $id_modello; ?>" data-id-parte="<?php echo $part->id; ?>" data-pos-madre="<?php echo $row['ordine']; ?>"><button type="button" class="btn btn-sm spostami" style="margin-top:5px;"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> <?php } ?></a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    </div>
                    <?php
                
                }
            }

        }
    ?>        

    </div>


    <?php 
        }
    } 
}
    ?>

<script src="js.js"></script>
</body>

</html>