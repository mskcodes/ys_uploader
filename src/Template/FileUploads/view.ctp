<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FileUpload $fileUpload
 */
?>
<?php echo $this->Html->script('jquery-3.3.1.min'); ?>
<?php echo $this->Html->script('jqgrid-export'); ?>
<script language="JavaScript"><!--

var file_id = <?= $this->Number->format($fileUpload->id) ?>;
var lock_status = <?php
if(!empty($fileUpload->password)){
    echo 1;
}else{
    echo 0;   
}
  ?>;

$(document).ready(function(){
    'use strict';
    // JavaScript で表示
    $('.download_link').on('click', function() {
        
            if(lock_status == 1){
              $("#mode").val("download");
              $("#del_btn").text("ダウンロード");
              $('#passwordModal').modal();                
            }else{
                  var action = "download";
                  postActionLink(action,file_id,$("#password").val());                
            }         
        

      
    });
    $('.delete_link').on('click', function() {
        if(confirm('本当に # '+file_id+'のファイルを削除してよろしいですか?')){
            
            if(lock_status == 1){
                $("#mode").val("delete");
                $("#del_btn").text("削除");
               $('#passwordModal').modal();                
            }else{
                  var action = "delete";
                  postActionLink(action,file_id,$("#password").val());                
            }            
 
        }
      
    });    
    // ダイアログ表示前にJavaScriptで操作する
    $('#passwordModal').on('show.bs.modal', function(event) {
        $("#password").val("");
     // var recipient = button.data('whatever');
      // var modal = $(this);
      // modal.find('.modal-body .recipient').text(recipient);
      //modal.find('.modal-body input').val(recipient);
    });
    // ダイアログ表示直後にフォーカスを設定する
    $('#passwordModal').on('shown.bs.modal', function(event) {
      $('#password').focus();
    });
    $('#passwordModal').on('click', '.modal-footer .btn-primary', function() {
      $('#passwordModal').modal('hide');
      var action = $("#mode").val();
      postActionLink(action,file_id,$("#password").val());
    });
    
    
    });
    
    
    function postActionLink(action,id,pass){
        
            //受診基本／詳細データをサーバーへポストする 
        var Url = "/ys_uploader/file-uploads/" + action + "/" + id ;
        //urlはプロジェクト名/コントローラー名アンダーライン型/アクション名小文字
    jqueryPost(Url, "Post", {'pass':pass});
        
    }
    
--></script>
<style>
    .img-frame{
        height:auto;
         max-height:650px;
        overflow: scroll;
    }
    
    
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        アップロードファイル&nbsp;&nbsp;&nbsp;
        <span>ID<?= $fileUpload->id ?></span>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-4">

          <div class="box box-solid box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">メニュー</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <ul class="nav nav-pills nav-stacked">
        <li class="active">
<?=$this->Html->link(' ファイルのダウンロード', array('action' => 'download'),
                array('class' => 'download_link glyphicon glyphicon-download','onclick'=>'return false;'))  ?>            
        <li class="separator"></li>

       <li><?=$this->Html->link(' アップロードファイル一覧', array('action' => 'index',$fileUpload->category_id),
                array('class' => 'glyphicon glyphicon-list'
                        ))  ?></li>  
         <li>
<?=$this->Html->link(' ファイルの削除', array('action' => 'delete'),
                array('class' => 'delete_link glyphicon glyphicon-trash','onclick'=>'return false;'))  ?>                
</li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->

        </div>
        <!-- /.col -->
        <div class="col-md-8">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><?php
              if(!empty($fileUpload->password)){
                  echo '<i class="fa fa-lock"></i>';
              }
              
              ?> ファイル詳細</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <div class="table-responsive">
      
    <table class="table vertical-table table-bordered table-hover table-striped">
            <tr class="bg-orange-active">
            <th>項目名</th>
            <th>データ</th>
            </tr>   
        <tr>
            <th scope="row"><?= __('カテゴリ') ?></th>
            <td><?php
            if(!empty($fileUpload->category->name)){
                echo h($fileUpload->category->name);
            }
              ?></td>
        </tr>                    
        <tr>
            <th scope="row"><?= __('ファイル名') ?></th>
            <td><?= h($fileUpload->file_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('ファイル種類') ?></th>
            <td><?= h($fileUpload->mime_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('サイズ') ?></th>
            <td><?php
              //サイズのフォーマット用
                function formatBytes($bytes, $precision = 2) {
                    $units = array('B', 'KB', 'MB', 'GB', 'TB');
                    $i = 0;
                
                    while($bytes > 1024) {
                        $bytes /= 1024;
                        $i++;
                    }
                    return round($bytes, $precision) . ' ' . $units[$i];
                }             
            
                echo h(formatBytes($fileUpload->file_size, 2)); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('内容') ?></th>
            <td><?= h($fileUpload->description) ?></td>
        </tr>        
        <tr>
        <tr>
            <th scope="row"><?= __('アップロード者') ?></th>
            <td><?= h($fileUpload->user->username) ?></td>
        </tr>        
        <tr>            
            <th scope="row"><?= __('Id') ?></th>
            <td id="file_id"><?= $this->Number->format($fileUpload->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('作成日') ?></th>
            <td><?= h($fileUpload->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('最終更新日') ?></th>
            <td><?= h($fileUpload->modified) ?></td>
        </tr>
    </table>                
                
              </div>
              <!-- /.responsive -->
<?php if(!empty($previewData) or !empty($fileName)): ?>            
<div class="row">


  
    <div class="col-xs-12 col-md-12">
 <legend>Preview</legend>       
  <div class="img-frame">     
        <?php
        if($fileType == "excel"){
            echo $previewData;
        }else if($fileType == "pdfimage"){
            echo '<img  class="img-responsive img-thumbnail center-block" src="data:image/png;base64,'.base64_encode($previewData).'" alt="thumb" />';      
            $previewData->clear();
        }else if($fileType == "pdf"){
     //pdfviewerプラグインを使用してfile内のpdfを表示する
                 //Use the html helper to generate a link (cannot be crossdomain);
            //$pdfurl = $this->Url->build('/pdf_viewer/files/cookbookdemo.pdf',true); //Using true for full url, cookbookdemo.pdf is an example in the webroot of the plugin
            
            $pdfurl = $this->Url->build('/files/'.$fileName,true);
            // echo $this->Url->build([
                // "controller" => "Posts",
                // "action" => "view",
                // "bar",
            // ]);
            //echo $this->Url->build('/posts', true);

            $vieweroptions = array(
                    'pdfurl'    =>  $pdfurl,
                    'class'     =>  'span6', //Class you want to give to canvas, use your own class. I use TwitterBootstrap so therefore i use the span6 (half page) class.
                    'scale'     =>  1.0, //The 'zoom' or 'scale' factor. I use 2 for making the PDF more sharp in displaying. 
                    'startpage' =>  1, //Starting page
                );
            
            //Get the PdfView element that renders the PDF
            echo $this->element('PdfViewer.viewer',$vieweroptions);

        }else{
             //write out the image to client browser width='".(200*$iw/$ih)."'
            echo "<img class='img-responsive img-thumbnail center-block' src='data:".$fileUpload->mime_type.";base64,".base64_encode($previewData)."' alt='thumb'>";        
            
        }
        
        ?>
        </div> 
    </div>
</div>              
 <?php endif; ?>             
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
<!-- モーダルダイアローグ -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="passwordModalLabel">ファイルに設定したパスワードを入力</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="password" class="control-label">パスワード</label>
            <input type="password" class="form-control" id="password">
          </div>
          <input type="hidden" class="form-control" id="mode" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
        <button type="button" class="btn btn-primary" id="del_btn">削除</button>
      </div>
    </div>
  </div>
</div>