<?php
/* Archivo para la gestión de TuBici
 */
// seteando variables
setlocale(LC_ALL,"spanish");
// Titulos según pantalla
function var_que($pan) {
    global $tit_sin, $tit_plu;
    if ($pan=="usu") {$tit_plu="USUARIOS";$tit_sin="USUARIO";}
    elseif ($pan=="bic") {$tit_plu="BICICLETAS";$tit_sin="BICICLETA";}
    elseif ($pan=="pos") {$tit_plu="POSTES";$tit_sin="POSTE";}
    elseif ($pan=="alq") {$tit_plu="ALQUILERES";$tit_sin="ALQUILER";}
}
//Conectando a la base de datos
function con() {
    $ser="localhost:3306";
    $usu="root";
    $cla="javier";
    global $id_con;
/* Si la conexión No ha podido establecer, se informa de ello */
    $id_con = @mysql_connect($ser, $usu, $cla)
        or die("<H3>No se ha podido establecer la conexión.
                  <P>Compruebe si está activado el servidor de bases de
                  datos MySQL.</H3>");
    // seleccionamos la base de datos
    mysql_select_db("tubici",$id_con);
    return $id_con;
}
// ejecuta la sentencia
function eje_sen($id_con,$tex_sen) {
    $dat =@mysql_query($tex_sen,$id_con) or die("<H3>No se ha podido realizar la consulta $id_con
        <P> $tex_sen -- MySQL.".mysql_errno()."  ".mysql_error()."</H3>");
    return $dat;
}
//lista usuarios
function lis_usu($id_con,$con) {
    echo ("<table width=\"650\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\" align=\"center\">
                <tbody><tr>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Código</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Nombre</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Fecha alta</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Activo </font></th>
                       <!-- <th bgcolor=\"#0000c0\" colspan=\"3\"><font color=\"white\">Operaciones</font></th> -->
                    </tr>");
    $dat=eje_sen($id_con,$con);
    // con el resultado de la sentencia sql mostramos la información
    while($fil = mysql_fetch_row($dat)) {
        $con_usu="select cod_usu from usu where cod_bic is not null and cod_usu =".$fil[0];
        $dat_usu=eje_sen($id_con,$con_usu);
        echo(" <tr>
                            <td><font size=\"-1\"><b>".$fil[0]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[1]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[2]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[3]."</b></font></td>
                             <td>
                                <table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
                                    <tbody>
                                        <tr>");
                                           if (mysql_num_rows($dat_usu)>0){
                                           echo(" <td>No editable</td>
                                            <td>No borrable</td>");}
                                            else{
                                            echo(" <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=edi_usu&amp;id=".$fil[0].">Editar</a>
                                            </td>
                                            <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=del_usu&amp;id=".$fil[0]."\">Borrar</a>
                                            </td> ");

                                            }
                                       echo( "</tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>");
    }
    mysql_free_result($dat);
}
//lista postes
function lis_pos($id_con,$con) {
    echo ("<table width=\"650\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\" align=\"center\">
                <tbody><tr>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Código</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Dirección</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Numero de plazas</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Plazas libres</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Activo </font></th>
                       <!-- <th bgcolor=\"#0000c0\" colspan=\"3\"><font color=\"white\">Operaciones</font></th> -->
                    </tr>");
    $dat=eje_sen($id_con,$con);
    // con el resultado de la sentencia sql mostramos la información
    while($fil = mysql_fetch_row($dat)) {
        $con_usu="select cod_pos from bic where cod_pos =".$fil[0];
        $dat_usu=eje_sen($id_con,$con_usu);
        echo(" <tr>
                            <td><font size=\"-1\"><b>".$fil[0]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[1]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[2]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[3]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[4]."</b></font></td>
                             <td>
                                <table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
                                     <tbody>
                                        <tr>");
                                           if (mysql_num_rows($dat_usu)>0){
                                           echo(" <td>No editable</td>
                                            <td>No borrable</td>");}
                                            else{
                                            echo(" <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=edi_pos&amp;id=".$fil[0].">Editar</a>
                                            </td>
                                            <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=del_pos&amp;id=".$fil[0]."\">Borrar</a>
                                            </td> ");
                                            }
                                       echo( "</tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>");
    }
    mysql_free_result($dat);
}
//lista bicicletas
function lis_bic($id_con,$con) {
    echo ("<table width=\"650\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\" align=\"center\">
                <tbody><tr>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Código</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Situación</font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Parking </font></th>
                        <th bgcolor=\"#0000c0\"><font color=\"white\">Activa </font></th>
                       <!-- <th bgcolor=\"#0000c0\" colspan=\"3\"><font color=\"white\">Operaciones</font></th> -->
                    </tr>");
      $dat=eje_sen($id_con,$con);
    while($fil = mysql_fetch_row($dat)) {
        $con_usu="select cod_usu from usu where usu.cod_bic =".$fil[0];
        $dat_usu=eje_sen($id_con,$con_usu);
                echo(" <tr>
                            <td><font size=\"-1\"><b>".$fil[0]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[1]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[2]."</b></font></td>
                            <td><font size=\"-1\"><b>".$fil[3]."</b></font></td>
                             <td>
                                <table cellspacing=\"0\" cellpadding=\"3\" border=\"0\">
                                    <tbody>
                                        <tr>");
                                           if (mysql_num_rows($dat_usu)>0){
                                           echo(" <td>No editable</td>
                                            <td>No borrable</td>");}
                                            else{
                                            echo(" <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=edi_bic&amp;id=".$fil[0].">Editar</a>
                                            </td>
                                            <td>
                                                <a href=".$_SERVER['PHP_SELF']."?operacion=del_bic&amp;id=".$fil[0]."\">Borrar</a>
                                            </td> ");

                                            }
                                       echo( "</tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>");
    }
    mysql_free_result($dat);
}
//numero de que tenemos (usuarios, bicis, postes)
function num_que($tab) {
    $con="select count(*) from $tab ";
    $dat=eje_sen(con(),$con);
    global $inf;
    $inf = mysql_fetch_row($dat);
    return $inf[0];
    mysql_free_result($dat);
}
//nuevo usuario
function nue_usu() {
    echo ("
</TR></TABLE><P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Alta de nuevo usuario</u></h2></FONT>
<FORM name='form9' method='post' action=\"ges_usu.php?operacion=nue_alt_usu\">
 <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
    			<FONT color='white'>Nombre</FONT>
        </TD>
        <TD>
            <input type='text' name='nom_usu' size='30' value = \"\" maxlength='25'>
        </TD>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
    			<FONT color='white'>Fecha de alta YYYY/MM/DD </FONT>
    	</TD>
        <TD>
            <input type='text' name='fec_ini' size='30' value = ".date ( "Y/m/d" )." maxlength='25'>
        </TD>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
                                    <FONT color='white'>Activo</FONT>
        </TD>
        <TD><select name='l_act'>
            <OPTION selected=\"\" value=1>Activo</OPTION>

        </TD>
    </TR>
 </TABLE>
<CENTER>
<INPUT type='hidden' NAME='id' value = '-1'>
                <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Alta Usuario\">
</CENTER></FORM>
");

}
//nuevo poste
function nue_pos() {
    echo ("
</TR></TABLE><P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Alta de nuevo poste</u></h2></FONT>
<FORM name='form9' method='post' action=\"ges_pos.php?operacion=nue_alt_pos\">
 <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
    			<FONT color='white'>Dirección del poste</FONT>
        </TD>
        <TD>
            <input type='text' name='dir_pos' size='30' value = \"\" maxlength='50'>
        </TD>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
    			<FONT color='white'>Número de plazas</FONT>
    	</TD>
        <TD>
            <input type='text' name='num_par' size='10' value = \" \" maxlength='10'>
        </TD>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
                                    <FONT color='white'>Activo</FONT>
        </TD>
        <TD><select name='l_act'>
            <OPTION selected=\"\" value=1>Activo</OPTION>

        </TD>
    </TR>
 </TABLE>
<CENTER>
<INPUT type='hidden' NAME='id' value = '-1'>
                <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Alta Poste\">
</CENTER></FORM>
");

}
//nueva bicicleta
function nue_bic($cod_pos_bic) {
    $id_con=con();
    echo ("
</TR></TABLE><P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Alta de nueva bici</u></h2></FONT>
<FORM name='form9' method='post' action=\"ges_bic.php?operacion=nue_bic\">
 <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
    <TR>
        <TD bgcolor='#0000C0' align=center width=240>
    			<FONT color='white'>Poste donde esta aparcada</FONT>
        </TD>
        <TD>
            <select name='cod_pos_bic'>");
    $con="select cod_pos, dir_pos from pos where l_act = 'S' ";
    $dat=eje_sen($id_con,$con);
    while($fil = mysql_fetch_row($dat)) {
        if ($cod_pos_bic==$fil[0]) {echo("<OPTION selected=\"\" value=".$fil[0].">".$fil[1]."</OPTION> ");}
        else {echo("<OPTION value=".$fil[0].">".$fil[1]."</OPTION> ");}
    }
    echo( " </TD>
    <td>
          <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Carga Plazas Poste\">
    </td>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=240>
    			<FONT color='white'>Plaza del poste donde esta aparcada</FONT>
    	</TD>
</form>
<FORM name='form9' method='post' action=\"ges_bic.php?operacion=nue_alt_bic\">
<input type=hidden name=cod_pos_bic value=".$cod_pos_bic.">
        <TD>
 <select name='cod_par_bic'>");
    if (strlen($cod_pos_bic)>0){
    $con="select num_par from pos where cod_pos =".$cod_pos_bic;
    $dat=eje_sen($id_con,$con);
    $fil = mysql_fetch_row($dat);
        for($i=1;$i<=$fil[0];$i++){
            $con = "select count(*) from bic where bic.cod_pos =".$cod_pos_bic." and bic.cod_par =".$i;
            $tab=eje_sen($id_con,$con);
            $lib = mysql_fetch_row($tab);
            if ($lib[0]=='0')echo("<OPTION value=".$i.">".$i."</OPTION> ");
               // echo("<OPTION value=".$i.">".$i."</OPTION> ");
        }
    }
    echo( " </TD>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=140>
                                    <FONT color='white'>Activa</FONT>
        </TD>
        <TD><select name='l_act'>
            <OPTION selected=\"\" value=1>Activo</OPTION>

        </TD>
    </TR>
 </TABLE>
<CENTER>
      <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Alta Bici\">
</CENTER>
</FORM>
");
    mysql_free_result($dat);
}
// muestra la pantalla de editar para usuario
function edi_usu($id_con,$id) {
    $con="select cod_usu,nom_usu,fec_ini,l_act from usu where cod_usu = $id";
    $dat=eje_sen($id_con,$con);
    $inf = mysql_fetch_row($dat);
    echo ("</TR></TABLE><P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Modificar art&iacute;culo</u></h2></FONT>
              <FORM name='form9' method='post' action=".$_SERVER['PHP_SELF']."?operacion=nue_edi_usu>
    <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Nombre del Usuario</FONT>
            </TD>
            <TD>
                <input type='text' name='nom_usu' size='25' value = \"".$inf[1]."\" maxlength='50'>
            </TD>
    	</TR>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Fecha de Alta</FONT>
            </TD>
            <TD>
                <input type='text' name='fec_ini' size='25' value = \"".$inf[2]."\" maxlength='50'>
            </TD>
        </TR>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Activo</FONT>
            </TD>
            <TD><select name='l_act'>");
    if ($inf[3]==N) {
        echo ("<OPTION selected=\"\" value=N>No activo</OPTION>
                                        <OPTION  value=S>Activo</OPTION>");
    }else {
        echo ("<OPTION value=N>No activo</OPTION>
                                        <OPTION selected=\"\" value=S>Activo</OPTION>");}
    echo ("</TR>
    </TABLE>
    <CENTER>
    <INPUT type='hidden' NAME='id' value = ".$inf[0].">
          <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Modificar usuario\">
    </CENTER>
</FORM>");
    mysql_free_result($dat);

}
// muestra la pantalla de editar para poste
function edi_pos($id_con,$id) {
    $con="select cod_pos,dir_pos,num_par,l_act from pos where cod_pos = $id";
    $dat=eje_sen($id_con,$con);
    $inf = mysql_fetch_row($dat);
    echo ("</TR></TABLE><P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Modificar Poste</u></h2></FONT>
              <FORM name='form9' method='post' action=".$_SERVER['PHP_SELF']."?operacion=nue_edi_pos>
    <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Dirección del poste</FONT>
            </TD>
            <TD>
                <input type='text' name='dir_pos' size='25' value = \"".$inf[1]."\" maxlength='50'>
            </TD>
    	</TR>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Número de plazas</FONT>
            </TD>
            <TD>
                <input type='text' name='num_par' size='25' value = \"".$inf[2]."\" maxlength='50'>
            </TD>
        </TR>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Activo</FONT>
            </TD>
            <TD><select name='l_act'>");
    if ($inf[3]==N) {
        echo ("<OPTION selected=\"\" value=N>No activo</OPTION>
                                        <OPTION  value=S>Activo</OPTION>");
    }else {
        echo ("<OPTION value=N>No activo</OPTION>
                                        <OPTION selected=\"\" value=S>Activo</OPTION>");}
    echo ("</TR>
    </TABLE>
    <CENTER>
    <INPUT type='hidden' NAME='id' value = ".$inf[0].">
          <INPUT TYPE='SUBMIT' NAME='pulsa' VALUE=\"Modificar poste\">
    </CENTER>
</FORM>");
    mysql_free_result($dat);

}
// muestra la pantalla de editar para bici
function edi_bic($id_con,$id,$cod_pos_bic) {
    //los datos de la bici a editar
    $con="select cod_bic,cod_pos,cod_par,l_act from bic where cod_bic = $id";
    $dat=eje_sen($id_con,$con);
    $inf = mysql_fetch_row($dat);
    // Si ya ha seleccionado un poste ...y como este procedimiento se llama varias veces...
    if (strlen($cod_pos_bic>0))$inf[1]=$cod_pos_bic;
 echo ("
</TR>
</TABLE>
<P><HR><A NAME='ancla'></A><FONT color='#0000C0'><h2><u>Modificar Bici</u></h2></FONT>
<FORM name='form9' method='post' action=\"ges_bic.php?operacion=edi_bic\">
 <TABLE BORDER='0' cellspacing='10' cellpadding='0' align='center' width='600'>
    <TR>
        <TD bgcolor='#0000C0' align=center width=240>
    			<FONT color='white'>Poste donde esta aparcada</FONT>
        </TD>
        <TD>
            <select name='cod_pos_bic'>");
    // las postes activos y el que ocupa la bici
    $con="select cod_pos, dir_pos from pos where l_act = 'S' ";
    $dat=eje_sen($id_con,$con);
    while($fil = mysql_fetch_row($dat)) {
        if ($inf[1]==$fil[0]) {echo("<OPTION selected=\"\" value=".$fil[0].">".$fil[1]."</OPTION> ");}
        else {echo("<OPTION value=".$fil[0].">".$fil[1]."</OPTION> ");}
    }
    echo( " </TD>
    <td>
          <INPUT TYPE='SUBMIT' NAME='car_pla' VALUE=\"Carga Plazas Poste\">
        <input type=hidden name=id value=".$id.">
    </td>
    </TR>
    <TR>
        <TD bgcolor='#0000C0' align=center width=240>
    			<FONT color='white'>Plaza del poste donde esta aparcada</FONT>
    	</TD>
        <TD>
 <select name='cod_par_bic'>");
    if (strlen($inf[1])>0){
     // las plazas del poste y la que ocupa la bici
    $con="select num_par from pos where cod_pos =".$inf[1];
    $dat=eje_sen($id_con,$con);
    $fil = mysql_fetch_row($dat);
        for($i=1;$i<=$fil[0];$i++){
           //Consulto si esta libre la plaza de ese poste
           $con = "select count(*) from bic where bic.cod_pos =".$inf[1]." and bic.cod_par =".$i;
            $tab=eje_sen($id_con,$con);
            $lib = mysql_fetch_row($tab);
            // Si la plaza es la de la bici editada la plaza es valida y se marca como seleccionada
            if ($inf[2]==$i) {echo("<OPTION selected=\"\" value=".$i.">".$i."</OPTION> ");}
            // Si no pero est libre sale sin seleccionar
            elseif ($lib[0]=='0'){echo("<OPTION value=".$i.">".$i."</OPTION> ");}
        }
    }
    echo( " </TD>
    </TR>
        <TR>
            <TD bgcolor='#0000C0' align=center width=140>
                <FONT color='white'>Activo</FONT>
            </TD>
            <TD><select name='l_act'>");
    if ($inf[3]==N) {
        echo ("<OPTION selected=\"\" value=N>No activo</OPTION>
                                        <OPTION  value=S>Activo</OPTION>");
    }else {
        echo ("<OPTION value=N>No activo</OPTION>
                                        <OPTION selected=\"\" value=S>Activo</OPTION>");}
    echo ("</TR>
 </TABLE>
<CENTER>
      <input type=hidden name=id value=".$id.">
      <INPUT TYPE='SUBMIT' NAME='gra_mod_bic' VALUE=\"Modifica Bici\">
</CENTER>
</FORM>
");
    mysql_free_result($dat);
}
function lis_pdf($id_con,$con,$tab) {
    $fichero_pdf = getcwd()."\TuBici.pdf";
   // echo $fichero_pdf;
    global $tit_sin;
    echo $tit_sin;
    $pdf = pdf_new();
    echo "pasado el pdf_new" ;
    pdf_open_file($pdf, $fichero_pdf);
    pdf_set_info($pdf,"Author","Javier Iranzo");
    pdf_set_info($pdf,"Creator","Para un proyecto educativo de Aula Mentor");
    pdf_set_info($pdf,"Title","Listado de ".ucfirst(strtolower($GLOBALS["tit_sin"])));
    //pdf_set_info($pdf,"Subject","Prueba");
    //pdf_set_info($pdf,"Keywords","Root, aliaj");
    pdf_set_info($pdf,"CustomField","Ejemplo 4 –Unidad 8 - Curso de PHP 5 ");
    pdf_begin_page($pdf, 595, 842);
    $fuente = PDF_findfont($pdf, "Courier", "winansi", 0);
    pdf_setfont($pdf, $fuente, 30);
    pdf_setcolor($pdf, "both", "rgb", 0.0, 0.0, 0.0, 0.0);
    // Escribimos el título de la página.
    pdf_show_xy($pdf,ucfirst(strtolower($GLOBALS["tit_plu"]))." de TuBici",100,750);
    pdf_setfont($pdf, $fuente, 10);
    if ($tab=="usu") pdf_show_xy($pdf,"código. nombre.                              fecha alta.   activo. ",100,720);
    elseif ($tab=="pos") pdf_show_xy($pdf,"código. dirección.                       N. de plazas.   activo. ",100,720);
    elseif ($tab=="bic") pdf_show_xy($pdf,"código. Situación./ alquilada a                             activo. ",100,720);
    $tex_pdf = "";
    $y=720;
    $dat=eje_sen($id_con,$con);
    // con el resultado de la sentencia sql mostramos la información
    while($fil = mysql_fetch_row($dat)) {
        $y = $y - 10;
        //$tex_pdf = $tex_pdf." ".chr(10)." ".$fil[0].$fil[1].$fil[2].$fil[3];
        pdf_show_xy($pdf,str_pad($fil[0],10," ",STR_PAD_BOTH).str_pad($fil[1],35).$fil[2]."  ".str_pad($fil[3],25," ",STR_PAD_BOTH),100,$y);
    }
    mysql_free_result($dat);

    //pdf_show($pdf,$tex_pdf);
    pdf_end_page($pdf);
    pdf_close($pdf);
    pdf_delete($pdf);
}
function clo($id_con) {
    $l_cle=@mysql_close($id_con) or die("<H3>No se ha podido cerrar la base de datos
                  <P> $id_con -- MySQL.</H3>");
}
?>