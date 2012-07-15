<?PHP
/*
    part-db version 0.1
    Copyright (C) 2005 Christoph Lechner
    http://www.cl-projects.de/

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

    $Id: showparts.php 442 2012-06-14 05:40:48Z bubbles.red@gmail.com $
*/

    require_once ('lib.php');

    $cid    = isset( $_REQUEST['cid'])    ? $_REQUEST['cid']          : '';
    $pid    = isset( $_REQUEST['pid'])    ? $_REQUEST['pid']          : '';
    $subcat = isset( $_REQUEST['subcat']) ? (bool)$_REQUEST['subcat'] : true;
    $action = isset( $_REQUEST['action']) ? $_REQUEST['action']       : 'default';

    // logical inverted text
    $subcat_text = $subcat ? 'ausblenden' : 'einblenden';

    if ( $action == 'dec')
    {
        // remove one part
        parts_stock_decrease( $pid);
    }

    if ( $action == 'inc')
    {
        // add one part
        parts_stock_increase( $pid);
    }


    /** new: 20120712 Udo Neist **/

    $tmpl = new vlibTemplate(BASE."/templates/$theme/vlib_head.tmpl");
    $tmpl -> setVar('head_title', 'Deviceinfo');
    $tmpl -> setVar('head_charset', $http_charset);
    $tmpl -> setVar('head_css', $css);
    $tmpl -> setVar('head_menu', true);
    $tmpl -> setVar('head_popup', true);
    $tmpl -> setVar('hide_id', $hide_id);
    $tmpl -> pparse();

    /** end: 20120712 Udo Neist **/

?>

<div class="outer">
    <h2>Sonstiges</h2>
    <div class="inner">
        <form action="" method="post">
            Unterkategorien:
            <input type="hidden" name="cid" value="<?php print $cid; ?>">
            <input type="hidden" name="subcat" value="<?php print (! $subcat); ?>">
            <input type="submit" value="<?php print $subcat_text; ?>">
        </form>
        <a href="newpart.php?cid=<?php print $cid; ?>" onclick="return popUp('newpart.php?cid=<?php print $cid; ?>');">Neues Teil in dieser Kategorie</a>
    </div>
</div>


<div class="outer">
    <h2>Anzeige der Kategorie &quot;<?php print category_get_name( $cid); ?>&quot;</h2>
    <div class="inner">
        <table>
            <tr class="trcat">
                <td></td>
                <td>Name</td>
                <td>Beschreibung</td>
                <?php print "<td>".($hide_mininstock ? "Bestand" : "Vorh./<br>Min.Best.")."</td>"; ?>
                <td>Footprint</td>
                <td>Lagerort</td>
                <td class="idclass">ID</td>
                <td>Datenbl&auml;tter</td>
                <td align="center">-</td>
                <td align="center">+</td>
            </tr>
            <?php
                $result   = parts_select_category( $cid, $subcat);
                $row_odd = true;
                while ( $data = mysql_fetch_assoc( $result))
                {
                    print_table_row( $row_odd, $data, $hide_mininstock);
                    $row_odd = ! $row_odd;
                }
            ?>
        </table>
    </div>
</div>
<?php
	$tmpl = new vlibTemplate(BASE."/templates/$theme/vlib_foot.tmpl");
	$tmpl -> pparse();
?>
