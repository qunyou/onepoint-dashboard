<?php
if (!isset($error)) {
    $error = false;
}
?>
<div ng-controller="AppController" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter">
    <div ng-show="uploader.isHTML5">
        <div class="well my-drop-zone" nv-file-over="" uploader="uploader">
            按滑鼠左鍵拖曳檔案至此區塊，放開滑鼠左鍵後檔案將立即加入待傳清單，一次最多上傳 50 個檔案
        </div>
    </div>
    或按此選擇檔案
    <input type="file" nv-file-select="" uploader="uploader" multiple  />

    <p>待傳檔案: @{{ uploader.queue.length }}</p>

    <table class="table">
        <thead>
            <tr>
                <th width="50%">檔名</th>
                <th ng-show="uploader.isHTML5">檔案大小</th>
                <th ng-show="uploader.isHTML5">上傳進度</th>
                <th>狀態</th>
                <th>功能</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="item in uploader.queue">
                <td><strong>@{{ item.file.name }}</strong></td>
                <td ng-show="uploader.isHTML5" nowrap>@{{ item.file.size/1024/1024|number:2 }} MB</td>
                <td ng-show="uploader.isHTML5">
                    <div class="progress" style="margin-bottom: 0;">
                        <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                    </div>
                </td>
                <td class="text-center">
                    <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                    <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                    <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                </td>
                <td nowrap>
                    <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                        <span class="glyphicon glyphicon-upload"></span> 上傳
                    </button>
                    <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                        <span class="glyphicon glyphicon-ban-circle"></span> 取消
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                        <span class="glyphicon glyphicon-trash"></span> 移除
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <div>
        <div>
            上傳進度
            <div class="progress" style="">
                <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
            <span class="glyphicon glyphicon-upload"></span> 上傳全部
        </button>
        <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
            <span class="glyphicon glyphicon-ban-circle"></span> 取消
        </button>
        <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
            <span class="glyphicon glyphicon-trash"></span> 移除全部
        </button>
    </div>
</div>
