<?php /* Smarty version 2.6.25-dev, created on 2015-09-16 07:57:11
         compiled from index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'index.tpl', 8, false),array('function', 'html_options', 'index.tpl', 26, false),array('modifier', 'default', 'index.tpl', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('task_number' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>



    <form action="" method="post">
          
        <div>
            <?php echo smarty_function_html_radios(array('name' => 'organization_form_id','options' => $this->_tpl_vars['organization_form'],'selected' => ((is_array($_tmp=@$this->_tpl_vars['ad_object']->organization_form_id)) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)),'separator' => "&nbsp;"), $this);?>

        </div>
          
        <div> <label><b id="your-name">Ваше имя</b></label>
            <input type="text" maxlength="40" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->seller_name)) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" name="seller_name" id="fld_seller_name">
        </div>
        <div> <label>Электронная почта</label>
            <input type="text" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->email)) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" name="email" id="fld_email">
        </div>
        
        <div> <label> <input type="checkbox" value="1" name="allow_mails" id="allow_mails" <?php echo $this->_tpl_vars['is_allow_mail']; ?>
><span>Я не хочу получать вопросы по объявлению по e-mail</span> </label> </div>
        
        <div> <label>Номер телефона</label> <input type="text" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->phone)) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" name="phone" id="fld_phone"></div>
                
        <div style="display: none;" id="params" > <label class="form-label ">
            Выберите параметры
        </label> <div id="filters">
        </div> </div>
        <div> <label for="region">Город</label> <?php echo smarty_function_html_options(array('name' => 'location_id','options' => $this->_tpl_vars['cities'],'selected' => $this->_tpl_vars['city_selected_id']), $this);?>

        
         <div> <label for="region">Категория</label>          <select title="Выберите категорию" name="category_id"  required>
       
            
            <?php $_from = $this->_tpl_vars['labels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['label']):
?>
                <optgroup label="<?php echo $this->_tpl_vars['label']; ?>
">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subcategories'][$this->_tpl_vars['key']],'selected' => ((is_array($_tmp=@$this->_tpl_vars['category_selected_id'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, ''))), $this);?>

                </optgroup>
            <?php endforeach; endif; unset($_from); ?> 
         </select> 
          </div>   
            
        <div id="f_title"> <label for="fld_title" >Название объявления</label> <input type="text" maxlength="50"  value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->title)) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" name="title" id="fld_title" required> </div>
    
        <div> <label for="fld_description" id="js-description-label">Описание объявления</label> <textarea maxlength="3000" name="description" id="fld_description" required><?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->description)) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</textarea> </div>
        <div> <label>Цена</label> <input type="text" maxlength="9" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['ad_object']->price)) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" name="price" required>&nbsp;<span>руб.</span> </div>
            
        <div>    
            <input type="submit" name=<?php echo $this->_tpl_vars['button_name']; ?>
 value=<?php echo $this->_tpl_vars['button_value']; ?>
 />
            <?php if ($this->_tpl_vars['button_name'] == 'edit'): ?> 
            <input type="hidden" name="id" id="hiddenField" value="<?php echo $this->_tpl_vars['default_edit_id']; ?>
" />
            <?php endif; ?>
        </div>
    </form>

<table border="1">
<tr>
    <td>Название</td>
    <td>Имя</td>
    <td>Цена</td>
    <td>Действие</td>
</tr>


<?php $_from = $this->_tpl_vars['object_storage']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['outer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['outer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['ad_object']):
        $this->_foreach['outer']['iteration']++;
?>
  <tr>
      
     <td><a href="?id=<?php echo $this->_tpl_vars['id']; ?>
&mode=show"><?php echo $this->_tpl_vars['ad_object']->title; ?>
</a></td>
     <td><?php echo $this->_tpl_vars['ad_object']->seller_name; ?>
</td>
     <td><?php echo $this->_tpl_vars['ad_object']->price; ?>
</td>
     <td><a href="?id=<?php echo $this->_tpl_vars['id']; ?>
&mode=delete">Удалить</a></td>
  </tr>
<?php endforeach; endif; unset($_from); ?>



</table>


