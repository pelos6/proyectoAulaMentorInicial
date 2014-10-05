<?php 
// separando las funciones de la pantalla
require ("dat_bic.php");
// que se gestiona en esta página
define("que","alq");
var_que(que);
?>
<html>
    <LINK HREF="tubici.css" REL="stylesheet" TYPE="text/css">
    <head>
        <title>Proyecto TuBici - Gestión de <?php echo(ucfirst(strtolower($tit_plu)))?></title>
    </head>
    <body>
        <table id="titulo">
            <tbody><tr>
                    <th>
                        GESTIÓN DE <?php echo($tit_plu)?>
                    </th>
                </tr>
            </tbody>
        </table><p>
        </p><center><p>
                <?php 
                $id_con=con();
                //comprobando lo que nos llega y metiendolo en variables
                if (!isset($_REQUEST["ope"]))$ope="";
                else {$ope=$_REQUEST["ope"];}
                if (!isset($_REQUEST["cod_usu"]))$cod_usu="";
                else {$cod_usu=$_REQUEST["cod_usu"];}
                if (!isset($_REQUEST["cod_pos"]))$cod_pos="";
                else {$cod_pos=$_REQUEST["cod_pos"];}
                if (!isset($_REQUEST["cod_par"]))$cod_par="";
                else {$cod_par=$_REQUEST["cod_par"];}
                if (!isset($_REQUEST["cod_bic"]))$cod_bic="";
                else {$cod_bic=$_REQUEST["cod_bic"];}
                switch ($ope) {
                    case "alq_bic": //pide una bici para alquilar y se le asigna la primera libre de ese poste
                        $con="select min(cod_par) from bic where cod_pos =".$cod_pos;
                        $dat=eje_sen($id_con,$con);
                        $fil = mysql_fetch_row($dat);
                        $pos_asi=$cod_pos;
                        $par_asi=$fil[0];
                        mysql_free_result($dat);
                        break;
                    case "ace_bic": //acepta la bici asignada
                        if (strlen($cod_usu)>0) {
                            $con="update usu set cod_bic =".$cod_bic." where cod_usu = ".$cod_usu;
                            eje_sen($id_con,$con);
                            $con="update bic set cod_pos = null , cod_par = null where cod_bic =".$cod_bic;
                            eje_sen($id_con,$con);}
                        break;
                    case "apa_bic": // aparca la bici indicada en el parking del poste
                        if (strlen($cod_bic)>0) {
                        $con="update bic set cod_pos=".$cod_pos.", cod_par=".$cod_par." where cod_bic= ".$cod_bic;
                        eje_sen($id_con,$con);
                        $con="update usu set cod_bic = null where cod_bic=".$cod_bic;
                        eje_sen($id_con,$con);}
                        break;
                }
                $con="select cod_pos, concat(cod_pos,' ',dir_pos),num_par,l_act from pos order by dir_pos";
                $dat_pos =@mysql_query($con,$id_con) or die("<H3>No se ha podido realizar la consulta
                  <P> $con -- MySQL.</H3>");
//                echo "<pre>";
//                print_r($_REQUEST);
//                echo "</pre>";
                //los postes y las plazas
                while($fil_pos = mysql_fetch_row($dat_pos)) {
                //dirección del poste
                    echo("<table width='100%' border='1'>
                            <tr>
                                <td width='120' colspan='2'>"
                        .$fil_pos[1].
                        "</td>");
                    //las plazas por poste
                    for($i=1;$i<=$fil_pos[2];$i++) {
                        echo("<td width='120' colspan='1'>plaza ".$i."</td>");
                    }
                    echo("</tr>");
                    //usuario que quiere alquilar una bici
                    echo("<tr><td>
                    <form action=".$_SERVER['PHP_SELF']."?ope=alq_bic method=\"POST\" name=\"form1\">
                        <select name='cod_usu'>");
                    // las usuarios activos que no tienen bici alquilada
                    $con_usu="select cod_usu, concat(cod_usu,' ',nom_usu) from usu where l_act = 'S' and cod_bic is null ";
                    $dat_usu =@mysql_query($con_usu,$id_con) or die("<H3>No se ha podido realizar la consulta
                        <P> $con_usu -- MySQL.</H3>");
                    //Cargando el desplegable de usuarios que pueden alquilar
                    while($fil_usu = mysql_fetch_row($dat_usu)) {
                        echo("<option value=".$fil_usu[0].">".$fil_usu[1]."</option> ");
                    }
                    echo("<input type=hidden name=cod_pos value=".$fil_pos[0].">
                        </td>
                        <td>
                                <input type='SUBMIT' value='Alquilar' name='bot_alq'>
                        </td>
                    </form>");
                    //las bicicletas aparcadas y los parkings disponibles
                    for($i=1;$i<=$fil_pos[2];$i++) {
                        $con="select cod_bic from bic where cod_pos ='".$fil_pos[0]."'and cod_par='".$i."'";
                        $dat_par =@mysql_query($con,$id_con) or die("<H3>No se ha podido realizar la consulta
                        <P> $con -- MySQL.</H3>");
                        // si en este poste/plaza hay aparcada una bici
                        if (mysql_num_rows($dat_par)>0) {
                        //vemos que bici esta aparcada aqui
                            $fil_par=mysql_fetch_row($dat_par);
                            // si es la que se asigna al usuario sale el boton para aceptar el alquiler
                            if ($ope=="alq_bic" and $pos_asi==$fil_pos[0] and $par_asi==$i) {
                                echo("<td>
                                        <form action=".$_SERVER['PHP_SELF']."?ope=ace_bic method=\"POST\" name=\"form2\">
                                    <input type='SUBMIT' value='Alq.    Bic ".$fil_par[0]."' name='bot_ace'>
                                    <input type=hidden name=cod_par value=".$i.">
                                    <input type=hidden name=cod_bic value=".$fil_par[0].">
                                    <input type=hidden name=cod_usu value=".$cod_usu.">
                                    <input type=hidden name=cod_pos value=".$fil_pos[0]."></td></form>");
                            //sino sale el código de la bici que esta aparcada
                            }else {echo("<td>bici. ".$fil_par[0]."</td>");}
                        // si en este poste/plaza NO hay aparcada una bici es que se puede aparcar una
                        }else {echo(
                            "<td>
                                <form action=".$_SERVER['PHP_SELF']."?ope=apa_bic method=\"POST\" name=\"form3\">
                                    <select name='cod_bic'>");
                            //Las bicicletas que se pueden devolver
                            $con_bic="select cod_bic from usu where cod_bic is not null";
                            $dat_bic =@mysql_query($con_bic,$id_con) or die("<H3>No se ha podido realizar la consulta
                                     <P> $con_bic -- MySQL.</H3>");
                            while($fil_bic = mysql_fetch_row($dat_bic)) {
                                echo("<option value=".$fil_bic[0].">".$fil_bic[0]."</option> ");
                            }
                            echo("<input type=hidden name='cod_par' value=".$i.">
                                    <input type=hidden name=cod_pos value=".$fil_pos[0].">
                                    <input type='SUBMIT' value='Apa.' name='bot_apa'>
                                </form>
                            </td>");}
                    }
                    echo("</table><hr>");
                }
                //liberando recursos del sistema
                mysql_free_result($dat_pos);
                mysql_free_result($dat_par);
                mysql_free_result($dat_usu);
                mysql_free_result($dat_bic);
                clo($id_con);
                ?>
                <a href="index.php"> Página inicial</a>
        </center>
    </body>
</html>


