<?php
/* Smarty version 3.1.31, created on 2019-10-04 18:01:01
  from "/home/sanchit987/public_html/sudo-su/formtools/themes/default/footer.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.31',
  'unifunc' => 'content_5d973b85d8d740_57926168',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0fe85b7a123ca98d3f99132015add579bd43885c' => 
    array (
      0 => '/home/sanchit987/public_html/sudo-su/formtools/themes/default/footer.tpl',
      1 => 1570189894,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d973b85d8d740_57926168 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_show_page_load_time')) require_once '/home/sanchit987/public_html/sudo-su/formtools/global/smarty_plugins/function.show_page_load_time.php';
?>

      </div>
    </td>
  </tr>
  </table>

</div>


<?php if ($_smarty_tpl->tpl_vars['footer_text']->value != '' || $_smarty_tpl->tpl_vars['g_enable_benchmarking']->value) {?>
  <div class="footer">
    <?php echo $_smarty_tpl->tpl_vars['footer_text']->value;?>

    <?php echo smarty_function_show_page_load_time(array(),$_smarty_tpl);?>

  </div>
<?php }?>

</body>
</html>
<?php }
}
