<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><?php echo $bean['tbl_comment']?>管理</h3>
      </div>

      <div class="title_right">

        <div class="col-md-5 col-sm-5 col-xs-12 pull-right text-right">
          <button class="btn btn-success" onClick="add_dialog()">添加<?php echo $bean['tbl_comment']?></button>
        </div>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <!-- table -->
        <table id="responsived-atatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>序号</th>
<?php /*----------生成表格头----------*/?>
<?php foreach ($bean['col'] as $column): //主表字段?>
              <th><?php echo $column['comment']?></th>
<?php endforeach //end主表字段?>
<?php if ($bean['join'] != null): //连接表字段?>
<?php   foreach ($bean['join'] as $join_table): ?>
<?php     foreach ($join_table['col'] as $column): ?>
              <th><?php echo $column['comment']?></th>
<?php     endforeach ?>
<?php   endforeach ?>
<?php endif //end连接表字段?>
<?php /*----------/生成表格头----------*/?>
              <th>修改</th>
              <th>删除</th>
            </tr>
          </thead>

        </table>
        <!-- /table -->

        <!-- add -->
        <form id="js-add-form" data-parsley-validate style="display: none">
<?php /*----------生成添加表单----------*/?>
<?php foreach ($bean['col'] as $column): //主表字段?>
          <label><?php echo $column['comment']?></label>
<?php   if ($column['type'] === 'input'): ?>
          <input name="<?php echo $column['field']?>" type="text" class="form-control"/>
<?php   elseif ($column['type'] === 'text'): ?>
          <script id="ue-<?php echo $column['field'] ?>" name="<?php echo $column['field'] ?>" type="text/plain"></script>
<?php   elseif ($column['type'] === 'file'): ?>
          <input id="fi-<?php echo $column['field'] ?>" name="<?php echo $column['field'] ?>" type="file" class="file" data-show-preview="false"> 
<?php   elseif ($column['type'] === 'time'): ?>
          <input name="<?php echo $column['field']?>" type="text" class="form-control"/>
<?php   endif; ?>
<?php endforeach //end主表字段?>
<?php if ($bean['join'] != null): //连接表字段?>
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
<?php     foreach ($join_table['manipulation_col'] as $join_table_mani_col): ?>
          <label><?php echo $join_table_mani_col['comment']?></label>
<?php       if ($join_table_mani_col['formtype'] == 'select'): //连接表字段类型是select?>
          <select name="<?php echo $join_table_name."[{$join_table_mani_col['field']}]" ?>" class="js-select-<?php echo $join_table_name."-".$join_table_mani_col['field'] ?> form-control"></select>
<?php       elseif ($join_table_mani_col['formtype'] == 'multichoice'): //连接表字段类型是multichoice?>
          <div class="row js-checkbox-<?php echo $join_table_name."-".$join_table_mani_col['field'] ?>"></div>
<?php       elseif ($join_table_mani_col['formtype'] == 'input'): //连接表字段类型是input?>
          <input name="<?php echo $join_table_name."[{$join_table_mani_col['field']}]" ?>" type="text">
<?php       endif ?>
<?php     endforeach ?>
<?php   endforeach ?>
<?php endif //end连接表字段?>
<?php /*----------/生成添加表单----------*/?>
          <br/>
          <span class="btn btn-primary">添加<?php echo $bean['tbl_comment']?></span>
        </form>
        <!-- /add -->

        <!-- edit -->
        <form id="js-edit-form" data-parsley-validate style="display: none">
<?php /*----------生成修改表单----------*/?>
<?php foreach ($bean['col'] as $column): //主表字段?>
          <label><?php echo $column['comment']?></label>
          <input name="<?php echo $column['field']?>" type="text" class="form-control"/>
<?php endforeach //end主表字段?>
<?php if ($bean['join'] != null): //连接表字段?>
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
<?php     foreach ($join_table['manipulation_col'] as $join_table_mani_col): ?>
          <label><?php echo $join_table_mani_col['comment']?></label>
<?php       if ($join_table_mani_col['formtype'] == 'multichoice'): //连接表字段类型是multichoice?>
          <div class="row js-checkbox-<?php echo $join_table_mani_col['field'] ?>"></div>
<?php       else: //连接表字段类型是其他?>
          <select name="<?php echo $join_table_mani_col['field'] ?>" class="js-select-<?php echo $join_table_mani_col['field'] ?> form-control"></select>
<?php       endif ?>
<?php     endforeach ?>
<?php   endforeach ?>
<?php endif //end连接表字段?>
<?php /*----------生成修改表单----------*/?>
          <input name="<?php echo $bean['id']['field']?>" type="text" style="display: none" />
          <br/>
          <span class="btn btn-primary">修改<?php echo $bean['tbl_comment']?></span>
        </form>
        <!-- /edit -->   
      </div>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
  function init_table(){
    window.DEP_TABLE = $('#responsived-atatable').DataTable({
      "ordering": false,
      "searching": false,
      "serverSide": true,
      "ajax": "<?php echo "<?=site_url('back/{$bean_name}/selectPage')?>"?>",
        
        "columns": [
          {"data":"<?php echo $bean['id']['field']?>" },
<?php foreach ($bean['col'] as $key => $column): ?>
          {"data":"<?php echo $column['field']?>" },
<?php endforeach ?>
<?php if ($bean['join'] != null): ?>
<?php   foreach ($bean['join'] as $join_table): ?>
<?php     foreach ($join_table['manipulation_col'] as $join_table_mani_col): ?>
<?php       if ($join_table_mani_col['formtype'] == 'multichoice'): //连接表字段类型是multichoice?>
          {
            "data":"<?php echo $join_table_mani_col['field']?>",
            "render": function(data) {
              var data = data ? data.split(',') : '';
              var div = '';
              $(data).each(function(){
                div += '<span class="label label-primary">'+this+'</span>';
              });
              
              return div;
            }
          },
<?php       else: //连接表字段类型是其他?>
          {"data":"<?php echo $join_table_mani_col['field']?>" },
<?php       endif ?>
<?php     endforeach ?>
<?php   endforeach ?>
<?php endif ?>
          { 
            "data": null,
            "render": function(data) {
              //data的数据中有"要处理一下
              $.each(data, function(index, value){
                if(value != null) data[index] = value.replace(/"/g, '\\"');
              });
              data = JSON.stringify(data);
              data = data.replace(/"/g, '&quot;');
              var editdiv = '<a class="edit green" onClick="edit_dialog(\''+data+'\')"><i class="fa fa-pencil bigger-130"></i>修改</a>';
              return '<div class="action-buttons">'+ editdiv +'</div>';
            }
          },
          { 
            "data": "<?php echo "{$bean['id']['field']}"?>",
            "render": function(data) {
              var deldiv = '<a class="del red" onClick="del_confirm('+data+')"><i class="fa fa-trash bigger-130"></i>删除</a>';
              return '<div class="action-buttons">'+ deldiv +'</div>';
            }
          }
        ],
      
      
        "language": {
            "processing": "处理中...",
            "lengthMenu": "显示 _MENU_ 项结果",
            "zeroRecords": "没有匹配结果",
            "info": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
            "infoEmpty": "显示第 0 至 0 项结果，共 0 项",
            "infoFiltered": "(由 _MAX_ 项结果过滤)",
            "infoPostFix": "",
            "search": "搜索:",
            "searchPlaceholder": "搜索...",
            "url": "",
            "emptyTable": "表中数据为空",
            "loadingRecords": "载入中...",
            "infoThousands": ",",
            "paginate": {
                "first": "首页",
                "previous": "上页",
                "next": "下页",
                "last": "末页"
            },
            "aria": {
                "paginate": {
                    first: '首页',
                    previous: '上页',
                    next: '下页',
                    last: '末页'
                },
                "sortAscending": ": 以升序排列此列",
                "sortDescending": ": 以降序排列此列"
            },
            "decimal": "-",
            "thousands": "."
        },
    });
  }
  

  function edit_dialog(data){
    data = data.replace(/&quot;/g, '"');
    data = JSON.parse(data);
    var validate_form = function() {
      if (true === $('#edit-form').parsley().isValid()) {
        $('.bs-callout-info').removeClass('hidden');
        $('.bs-callout-warning').addClass('hidden');
      } else {
        $('.bs-callout-info').addClass('hidden');
        $('.bs-callout-warning').removeClass('hidden');
      }
    };

    var init_dialog = function($edit_form){
<?php foreach ($bean['col'] as $key => $column): //初始化主表默认值?>
      $edit_form.find(":input[name='<?php echo $column['field']?>']").val(data['<?php echo $column['field']?>']);
<?php endforeach ?>
<?php if (isset($bean['join'])): //初始化连接表默认值?>
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
      $edit_form.find(":input[name='<?php echo $join_table['pri_field']?>']").val(data['<?php echo $join_table['pri_field']?>']);
<?php   endforeach ?>
<?php endif ?>
      $edit_form.find(":input[name='<?php echo $bean['id']['field']?>']").val(data['<?php echo $bean['id']['field']?>']);
      $/*.listen*/('parsley:field:validate', function() {
        validate_form();
      });
      $edit_form.find(".btn").on('click', function() {
        //parsley()和serialize()不能用$edit_form（是div节点）必须用$('#edit-form')
        if ($('#edit-form').parsley().validate()) {
          var post_data = $('#edit-form').serialize();
          $.post("<?php echo "<?=site_url('back/{$bean_name}/update')?>"?>", post_data, function(data){
            if (data['status'] == true) {
              DEP_TABLE.ajax.reload( null, false );
              edit_dialog.setContent('修改成功');
            }else{
              edit_dialog.setContent(data['message']);
            }
          });
        }       
        validate_form();
      });
<?php /*----------初始化富文本编辑器----------*/?>
<?php foreach ($bean['col'] as $key => $column): //主表字段?>
<?php   if ($column['type'] == 'text'): ?>
      $edit_form.find(":input[name='<?php echo $column['field'] ?>']").replaceWith(function(){return '<script id="ue-<?php echo $column['field'] ?>" name="<?php echo $column['field'] ?>" type="text/plain">'+$(this).val()+'<\/script>'});
      var ue_width = $edit_form.width();
      // jquery-confirm的zindex是8个9，UE要9个9在jquery-confirm上面
      var ue = UE.getEditor('ue-<?php echo $column['field'] ?>',{initialFrameWidth:ue_width,zIndex:999999999,autoFloatEnabled:false});
<?php   endif ?>
<?php endforeach ?>
<?php /*----------/初始化富文本编辑器----------*/?>

<?php /*----------初始化multichoice----------*/?>
<?php if (isset($bean['join'])): ?>
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
<?php     if (isset($join_table['form_type']) && $join_table['form_type'] == 'multichoice'): ?>
      var checked_<?php echo $join_table['pri_field']?>_array = data['<?php echo $join_table['pri_field']?>'].split(',');
      $edit_form.find('.js-checkbox-<?php echo $join_table_name?> input').each(function(){
        var self = $(this),
            label = self.next(),
            label_text = label.text();
        if ($.inArray(self.val(), checked_<?php echo $join_table['pri_field']?>_array) != -1) {
          self.prop('checked',true);
        };
        label.remove();
        self.iCheck({
          checkboxClass: 'icheckbox_line-green',
          insert: '<div class="icheck_line-icon"></div>' + label_text
        });
      });
<?php     endif ?>
<?php   endforeach ?>
<?php endif ?>
<?php /*----------/初始化multichoice----------*/?>
    }

    var edit_dialog = $.dialog({
        title: '修改<?php echo $bean['tbl_comment']?>',
        content: function(){
          return '<form id="edit-form" data-parsley-validate>' + $('#js-edit-form').html() + '</form>';
        },
        onContentReady: function(){
          init_dialog(edit_dialog.$content);
        },
        onDestroy: function(){
<?php foreach ($bean['col'] as $key => $column): ?>
<?php   if ($column['type'] == 'text'): ?>
          UE.getEditor('ue-<?php echo $column['field'] ?>').destroy();
<?php   endif ?>
<?php endforeach ?>
        }
    });
  }



  function add_dialog(data){
    var validate_form = function() {
      if (true === $('#add-form').parsley().isValid()) {
        $('.bs-callout-info').removeClass('hidden');
        $('.bs-callout-warning').addClass('hidden');
      } else {
        $('.bs-callout-info').addClass('hidden');
        $('.bs-callout-warning').removeClass('hidden');
      }
    };
    
    var init_dialog = function($add_form){
      $/*.listen*/('parsley:field:validate', function() {
        validate_form();
      });
      $add_form.find('.btn').on('click', function() {
        //parsley()和serialize()不能用$add_form（是div节点）必须用$('#add-form')
        if ($('#add-form').parsley().validate()) {
          var post_data = new $('#add-form').serialize();
          $.post("<?php echo "<?=site_url('back/{$bean_name}/insert')?>"?>", post_data, function(data){
            if (data['status'] == true) {
              DEP_TABLE.ajax.reload( null, false );
              add_dialog.setContent('添加成功');
            }else{
              add_dialog.setContent(data['message']);
            }
          })
        }

      });
<?php /*----------初始化富文本编辑器----------*/?>
<?php foreach ($bean['col'] as $key => $column): //主表字段?>
<?php   if ($column['type'] == 'text'): ?>
      $add_form.find(":input[name='<?php echo $column['field'] ?>']").replaceWith('<script id="ue-<?php echo $column['field'] ?>" name="<?php echo $column['field'] ?>" type="text/plain"><\/script>');
      var ue_width = $add_form.width();
      // jquery-confirm的zindex是8个9，UE要9个9在jquery-confirm上面
      var ue = UE.getEditor('ue-<?php echo $column['field'] ?>',{initialFrameWidth:ue_width,zIndex:999999999,autoFloatEnabled:false});
<?php   endif ?>
<?php endforeach ?>
<?php /*----------/初始化富文本编辑器----------*/?>

<?php if (isset($bean['join'])): ?>
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
<?php     if (isset($join_table['form_type']) && $join_table['form_type'] == 'multichoice'): ?>
    $add_form.find('.js-checkbox-<?php echo $join_table_name?> input').each(function(){
      var self = $(this),
          label = self.next(),
          label_text = label.text();
        label.remove();
        self.iCheck({
          checkboxClass: 'icheckbox_line-green',
          insert: '<div class="icheck_line-icon"></div>' + label_text
        });
      });
<?php     endif ?>
<?php   endforeach ?>
<?php endif ?>
    }

    var add_dialog = $.dialog({
        title: '添加<?php echo $bean['tbl_comment']?>',
        content: function(){
          return '<form id="add-form" data-parsley-validate>' + $('#js-add-form').html() + '</form>';
        },
        onContentReady: function(){
          init_dialog(add_dialog.$content);
        },
        onDestroy: function(){
<?php foreach ($bean['col'] as $key => $column): ?>
<?php   if ($column['type'] == 'text'): ?>
          UE.getEditor('ue-<?php echo $column['field'] ?>').destroy();
<?php   endif ?>
<?php endforeach ?>
        }
    });
  }

  function del_confirm(data){
    var del_confirm = $.confirm({
        title: '删除',
        content: '是否删除该数据，序号'+data,
        buttons: {
          confirm: {
            text: '确认',
            btnClass : 'btn-danger',
            action : function(){
              $.post("<?php echo "<?=site_url('back/{$bean_name}/delete')?>"?>", {<?php echo $bean['id']['field']?> : data}, function(data,status){
                if (data['status'] == true) {
                  DEP_TABLE.ajax.reload( null, false );
                  $.dialog('删除成功');
                }
              });
            }
          },
          cancel: {
            text: '取消',
            btnClass : 'btn-info'
          }
        }
    });
  }

<?php /*----------初始化添加，修改表单（对在表单中选择用的外链表数据初始化）----------*/?>
<?php if ($bean['join'] != null): ?>
  function init_add_edit_form(){
    $.post("<?php echo "<?=site_url('back/{$bean_name}/get_form_data')?>"?>", {}, function(data,status){
      if (data['status'] == true) {
<?php   foreach ($bean['join'] as $join_table_name => $join_table): ?>
<?php     foreach ($join_table['manipulation_col'] as $join_table_mani_col): ?>
<?php       if ($join_table_mani_col['formtype'] == 'multichoice'): ?>
          var $<?php echo $join_table_name?> = $('.js-checkbox-<?php echo $join_table_name?>');
          $(data.<?php echo $join_table_name?>).each(function(){
            var checkbox_str = '<div class="col-md-4"><input name="<?php echo $join_table['pri_field'] ?>[]" type="checkbox" value="'+this.<?php echo $join_table['join_field'] ?>+'" />'+'<label>'+this.<?php echo $join_table['join_show_field'] ?>+'</label></div>';
            $<?php echo $join_table_name?>.append(checkbox_str);
          });
<?php       else: ?>
          var $<?php echo $join_table_name?> = $('.js-select-<?php echo $join_table_name?>');
          $(data.<?php echo $join_table_name ?>).each(function(){
            var option_str = '<option value="'+this.<?php echo $join_table['join_field'] ?>+'">'+this.<?php echo $join_table_mani_col['option_field'] ?>+'</option>'
            $<?php echo $join_table_name?>.append(option_str);
          });
<?php       endif ?> 
<?php     endforeach ?>      
<?php   endforeach ?>
      }
    });
  }
<?php endif ?>
<?php /*----------/初始化添加，修改表单（对在表单中选择用的外链表数据初始化）----------*/?>

  window.onload = function(){
    init_table();
<?php if (isset($bean['join'])): ?>
    init_add_edit_form();
<?php endif ?>
  }
</script>


