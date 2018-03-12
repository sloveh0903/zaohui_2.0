/*global plupload */
/*global qiniu */
function FileProgress(file, targetID) {
    this.fileProgressID = file.id;
    this.file = file;
    //strFileName = file.name.substring(0, file.name.lastIndexOf('.'));
    this.opacity = 100;
    this.height = 0;
    this.fileProgressWrapper = $('#' + this.fileProgressID);
    if (!this.fileProgressWrapper.length) {
        // <div class="progress">
        //   <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
        //     <span class="sr-only">20% Complete</span>
        //   </div>
        // </div>
        this.fileProgressWrapper = $('<li></li>');
        var Wrappeer = this.fileProgressWrapper;
        Wrappeer.attr('id', this.fileProgressID).addClass('progressContainer');
        var videobox = $("<div/>");
        var progressText = $("<h5/>");
        videobox.addClass('video-heading');
        //var b = new Base64();
        //strFileName = b.decode(strFileName);
        //progressText.addClass('progressName').text(strFileName);
        progressText.addClass('progressName').text(getName(file.name));
        var progressbtn = $("<div/>");
        progressbtn.addClass('video-curr-operate');
        var fileSize = plupload.formatSize(file.size).toUpperCase();
        var progressBarTd = $("<div/>");
        progressBarTd.addClass('video-upload-detail');
        var progressBarBox = $("<div/>");
        progressBarBox.addClass('info');
        var progressBarWrapper = $("<div/>");
        progressBarWrapper.addClass("progress progress-striped");

        var progressBar = $("<div/>");
        progressBar.addClass("progress-bar progress-bar-info")
            .attr('role', 'progressbar')
            .attr('aria-valuemax', 100)
            .attr('aria-valuenow', 0)
            .attr('aria-valuein', 0)
            .width('0%');

        var progressBarPercent = $('<span class=sr-only />');
        progressBarPercent.text(fileSize);



        progressBar.append(progressBarPercent);
        progressBarWrapper.append(progressBar);
        progressBarBox.append(progressBarWrapper);
        var progressBarStatus = $('<div class="status text-center"/>');
        progressBarBox.append(progressBarStatus);
        var progressCancel = $('<a href=javascript:; />');
        progressCancel.show().addClass('progressCancel').text('×');
        progressBarBox.append(progressCancel);
        progressBarTd.append(progressBarBox);

        videobox.append(progressText);
        videobox.append(progressbtn);
        videobox.append(progressBarTd);

        Wrappeer.append(videobox);
        $(targetID).append(Wrappeer);
    } else {
        this.reset();
    }

    this.height = this.fileProgressWrapper.offset().top;
    this.setTimer(null);
}




/*
function FileProgress(file, targetID) {
  this.fileProgressID = file.id;
  this.file = file;

  this.opacity = 100;
  this.height = 0;
  this.fileProgressWrapper = $('#' + this.fileProgressID);
  if (!this.fileProgressWrapper.length) {
    // <div class="progress">
    //   <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
    //     <span class="sr-only">20% Complete</span>
    //   </div>
    // </div>

    this.fileProgressWrapper = $('<tr></tr>');
    var Wrappeer = this.fileProgressWrapper;
    Wrappeer.attr('id', this.fileProgressID).addClass('progressContainer');

    var progressText = $("<td/>");
    progressText.addClass('progressName').text(file.name);


    var fileSize = plupload.formatSize(file.size).toUpperCase();
    var progressSize = $("<td/>");
    progressSize.addClass("progressFileSize").text(fileSize);

    var progressBarTd = $("<td/>");
    var progressBarBox = $("<div/>");
    progressBarBox.addClass('info');
    var progressBarWrapper = $("<div/>");
    progressBarWrapper.addClass("progress progress-striped");

    var progressBar = $("<div/>");
    progressBar.addClass("progress-bar progress-bar-info")
      .attr('role', 'progressbar')
      .attr('aria-valuemax', 100)
      .attr('aria-valuenow', 0)
      .attr('aria-valuein', 0)
      .width('0%');

    var progressBarPercent = $('<span class=sr-only />');
    progressBarPercent.text(fileSize);

    var progressCancel = $('<a href=javascript:; />');
    progressCancel.show().addClass('progressCancel').text('×');

    progressBar.append(progressBarPercent);
    progressBarWrapper.append(progressBar);
    progressBarBox.append(progressBarWrapper);
    progressBarBox.append(progressCancel);

    var progressBarStatus = $('<div class="status text-center"/>');
    progressBarBox.append(progressBarStatus);
    progressBarTd.append(progressBarBox);

    Wrappeer.append(progressText);
    Wrappeer.append(progressSize);
    Wrappeer.append(progressBarTd);

    $('#' + targetID).append(Wrappeer);
  } else {
    this.reset();
  }

  this.height = this.fileProgressWrapper.offset().top;
  this.setTimer(null);
}*/

FileProgress.prototype.setTimer = function(timer) {
  this.fileProgressWrapper.FP_TIMER = timer;
};

FileProgress.prototype.getTimer = function(timer) {
  return this.fileProgressWrapper.FP_TIMER || null;
};

FileProgress.prototype.reset = function() {
  this.fileProgressWrapper.attr('class', "progressContainer");
  this.fileProgressWrapper.find('td .progress .progress-bar-info').attr(
    'aria-valuenow', 0).width('0%').find('span').text('');
  this.appear();
};

FileProgress.prototype.setChunkProgess = function(chunk_size) {
  var chunk_amount = Math.ceil(this.file.size / chunk_size);
  if (chunk_amount === 1) {
    return false;
  }

  var viewProgess = $('<button class="btn btn-default">查看分块上传进度</button>');

  var progressBarChunkTr = $(
    '<tr class="chunk-status-tr"><td colspan=3></td></tr>');
  var progressBarChunk = $('<div/>');
  for (var i = 1; i <= chunk_amount; i++) {
    var col = $('<div class="col-md-2"/>');
    var progressBarWrapper = $('<div class="progress progress-striped"></div');

    var progressBar = $("<div/>");
    progressBar.addClass("progress-bar progress-bar-info text-left")
      .attr('role', 'progressbar')
      .attr('aria-valuemax', 100)
      .attr('aria-valuenow', 0)
      .attr('aria-valuein', 0)
      .width('0%')
      .attr('id', this.file.id + '_' + i)
      .text('');

    var progressBarStatus = $('<span/>');
    progressBarStatus.addClass('chunk-status').text();

    progressBarWrapper.append(progressBar);
    progressBarWrapper.append(progressBarStatus);

    col.append(progressBarWrapper);
    progressBarChunk.append(col);
  }

  if (!this.fileProgressWrapper.find('td:eq(2) .btn-default').length) {
    this.fileProgressWrapper.find('td>div').append(viewProgess);
  }
  progressBarChunkTr.hide().find('td').append(progressBarChunk);
  progressBarChunkTr.insertAfter(this.fileProgressWrapper);

};

FileProgress.prototype.setProgress = function(percentage, speed, chunk_size) {
  this.fileProgressWrapper.attr('class', "progressContainer green");

  var file = this.file;
  var uploaded = file.loaded;


  var size = plupload.formatSize(uploaded).toUpperCase();
  var formatSpeed = plupload.formatSize(speed).toUpperCase();
  var progressbar = this.fileProgressWrapper.find('div .progress').find(
    '.progress-bar-info');
  if (this.fileProgressWrapper.find('.status').text() === '取消上传') {
    return;
  }
  this.fileProgressWrapper.find('.status').text("已上传: " + size + " 速度： " +
    formatSpeed + "/s");
  percentage = parseInt(percentage, 10);
  if (file.status !== plupload.DONE && percentage === 100) {
    percentage = 99;
  }

  progressbar.attr('aria-valuenow', percentage).css('width', percentage + '%');

  if (chunk_size) {
    var chunk_amount = Math.ceil(file.size / chunk_size);
    if (chunk_amount === 1) {
      return false;
    }
    var current_uploading_chunk = Math.ceil(uploaded / chunk_size);
    var pre_chunk, text;

    for (var index = 0; index < current_uploading_chunk; index++) {
      pre_chunk = $('#' + file.id + "_" + index);
      pre_chunk.width('100%').removeClass().addClass('alert-success').attr(
        'aria-valuenow', 100);
      text = "块" + index + "上传进度100%";
      pre_chunk.next().html(text);
    }

    var currentProgessBar = $('#' + file.id + "_" + current_uploading_chunk);
    var current_chunk_percent;
    if (current_uploading_chunk < chunk_amount) {
      if (uploaded % chunk_size) {
        current_chunk_percent = ((uploaded % chunk_size) / chunk_size * 100).toFixed(
          2);
      } else {
        current_chunk_percent = 100;
        currentProgessBar.removeClass().addClass('alert-success');
      }
    } else {
      var last_chunk_size = file.size - chunk_size * (chunk_amount - 1);
      var left_file_size = file.size - uploaded;
      if (left_file_size % last_chunk_size) {
        current_chunk_percent = ((uploaded % chunk_size) / last_chunk_size *
          100).toFixed(2);
      } else {
        current_chunk_percent = 100;
        currentProgessBar.removeClass().addClass('alert-success');
      }
    }
    currentProgessBar.width(current_chunk_percent + '%');
    currentProgessBar.attr('aria-valuenow', current_chunk_percent);
    text = "块" + current_uploading_chunk + "上传进度" + current_chunk_percent +
      '%';
    currentProgessBar.next().html(text);
  }

  this.appear();
};

FileProgress.prototype.setComplete = function(up, info,video) {
  var operate = this.fileProgressWrapper.find('.video-curr-operate'),
      detail = this.fileProgressWrapper.find('.video-upload-detail');
  detail.hide();
 var b = new Base64();
 //video.title = b.decode(video.title);
 //video.title = b.decode(video.title);
  var str = '<i data-status="off" class="setfree" data-id="'+video.video_id+'"></i>\n' +
      '<font style="font-size: 14px;color:rgba(41,43,51,.3);margin:0 6px 0 10px">试看</font>\n' +
      '<img class="video-up" data-id="'+video.video_id+'"\n' +
      '                                             src="/public/static/merch/images/video_up@2x20.png">\n' +
      '                                        <img class="video-down" data-id="'+video.video_id+'"\n' +
      '                                             src="/public/static/merch/images/video_down@2x20.png">\n' +
      '                                        <span class="hidden-video" data-audit="1" data-id="'+video.video_id+'">隐藏</span>\n' +
      '                                        <span class="move-video" data-id="'+video.video_id+'">移动</span>\n' +
      '                                        <span class="edit-video" data-id="'+video.video_id+'" data-title="'+video.title+'">编辑</span>\n' +
      '                                        <span class="delete-video" data-id="'+video.video_id+'">删除</span>';
  operate.append(str);
  operate.show();
};
FileProgress.prototype.setError = function() {
  this.fileProgressWrapper.find('td:eq(2)').attr('class', 'text-warning');
  this.fileProgressWrapper.find('td:eq(2) .progress').css('width', 0).hide();
  this.fileProgressWrapper.find('button').hide();
  this.fileProgressWrapper.next('.chunk-status-tr').hide();
};

FileProgress.prototype.setCancelled = function(manual) {
  var progressContainer = 'progressContainer';
  if (!manual) {
    progressContainer += ' red';
  }
  this.fileProgressWrapper.remove();
  // $(self).parents('li').remove();
  this.fileProgressWrapper.attr('class', progressContainer);
  this.fileProgressWrapper.find('.video-upload-detail .progress').remove();
  this.fileProgressWrapper.find('.info .btn-default').hide();
  this.fileProgressWrapper.find('.info .progressCancel').hide();
};

FileProgress.prototype.setStatus = function(status, isUploading) {
  if (!isUploading) {
    this.fileProgressWrapper.find('.status').text(status).attr('class',
      'status text-left');
  }
};

// 绑定取消上传事件
FileProgress.prototype.bindUploadCancel = function(up) {
  var self = this;
  if (up) {
    self.fileProgressWrapper.find('.video-upload-detail .progressCancel').on('click',
      function() {
          up.removeFile(self.file);
          self.fileProgressWrapper.remove();
        // self.setCancelled(false, this);
        // self.setStatus("取消上传");
        // self.fileProgressWrapper.find('.status').css('left', '0');
          // $('.addVideo-body').on('click', '.progressCancel', function () {
          // console.log('remove');
          // console.log(this)
          // })
      });
  }

};

FileProgress.prototype.appear = function() {
  if (this.getTimer() !== null) {
    clearTimeout(this.getTimer());
    this.setTimer(null);
  }

  if (this.fileProgressWrapper[0].filters) {
    try {
      this.fileProgressWrapper[0].filters.item(
        "DXImageTransform.Microsoft.Alpha").opacity = 100;
    } catch (e) {
      // If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
      this.fileProgressWrapper.css('filter',
        "progid:DXImageTransform.Microsoft.Alpha(opacity=100)");
    }
  } else {
    this.fileProgressWrapper.css('opacity', 1);
  }

  this.fileProgressWrapper.css('height', '');

  this.height = this.fileProgressWrapper.offset().top;
  this.opacity = 100;
  this.fileProgressWrapper.show();

};

function getName(upFileName) {
    var index1 = upFileName.lastIndexOf(".");
    var index2 = upFileName.length;
    var suffix = upFileName.substring(index1 + 1, index2);//后缀名
    var suffix2 = upFileName.substring(0, index1);//前缀名
    return suffix2;
}
