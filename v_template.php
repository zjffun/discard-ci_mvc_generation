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
<?php foreach ($bean['col'] as $key => $column): ?>
              <th><?php echo $column['comment']?></th>
<?php endforeach ?>
              <th>修改</th>
              <th>删除</th>
            </tr>
          </thead>

        </table>
        <!-- /table -->

        <!-- add -->
        <form id="js-add-form" data-parsley-validate style="display: none">
<?php foreach ($bean['col'] as $key => $column): ?>
          <label><?php echo $column['comment']?></label>
          <input name="<?php echo $column['field']?>" type="text" class="form-control"/>
<?php endforeach ?>
          <br/>
          <span class="btn btn-primary">添加<?php echo $bean['tbl_comment']?></span>
        </form>
        <!-- /add -->

        <!-- edit -->

        <form id="js-edit-form" data-parsley-validate style="display: none">
<?php foreach ($bean['col'] as $key => $column): ?>
          <label><?php echo $column['comment']?></label>
          <input name="<?php echo $column['field']?>" type="text" class="form-control"/>
<?php endforeach ?>
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
          { 
            "data": null,
            "render": function(data) {
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
<?php foreach ($bean['col'] as $key => $column): ?>
      $edit_form.find(":input[name='<?php echo $column['field']?>']").val(data['<?php echo $column['field']?>']);
<?php endforeach ?>
      $edit_form.find(":input[name='<?php echo $bean['id']['field']?>']").val(data['<?php echo $bean['id']['field']?>']);
      $/*.listen*/('parsley:field:validate', function() {
        validate_form();
      });
      $('#edit-form .btn').on('click', function() {
        if ($('#edit-form').parsley().validate()) {
          var post_data = new Object();
<?php foreach ($bean['col'] as $key => $column): ?>
          post_data['<?php echo $column['field']?>'] = $("#edit-form :input[name='<?php echo $column['field']?>']").val();
<?php endforeach ?>
          post_data['<?php echo $bean['id']['field']?>'] = $("#edit-form :input[name='<?php echo $bean['id']['field']?>']").val();
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
    }

    var edit_dialog = $.dialog({
        title: '修改<?php echo $bean['tbl_comment']?>',
        content: function(){
          return '<form id="edit-form" data-parsley-validate>' + $('#js-edit-form').html() + '</form>';
        },
        onContentReady: function(){
          init_dialog(edit_dialog.$content);
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
    

    var init_dialog = function(add_form){
      $/*.listen*/('parsley:field:validate', function() {
        validate_form();
      });
      add_form.find('.btn').on('click', function() {
        if ($('#add-form').parsley().validate()) {
          var post_data = new Object();
<?php foreach ($bean['col'] as $key => $column): ?>
          post_data['<?php echo $column['field']?>'] = $("#add-form :input[name='<?php echo $column['field']?>']").val();
<?php endforeach ?>
          $.post("<?php echo "<?=site_url('back/{$bean_name}/insert')?>"?>", post_data, function(data){
            if (data['status'] == true) {
              DEP_TABLE.ajax.reload( null, false );
              add_dialog.setContent('添加成功');
            }else{
              add_dialog.setContent(data['message']);
            }
          })
        }
      })
    }

    var add_dialog = $.dialog({
        title: '添加<?php echo $bean['tbl_comment']?>',
        content: function(){
          return '<form id="add-form" data-parsley-validate>' + $('#js-add-form').html() + '</form>';
        },
        onContentReady: function(){
          init_dialog(add_dialog.$content);
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




  window.onload = function(){
    init_table();
  }
</script>


