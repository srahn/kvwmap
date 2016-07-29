<div style_="width:100%;" onload='onLoadConvert'>
  <h1>XPlanGML Konverter</h1>
  <iframe id="converter-frame" style="width:100%;height:500px;border:0;" srcdoc="
  <?php
    include 'iconvert.php';
    // configure iconvert
    // show iframe
    show($this->initialData);
    ?>
  "></iframe>
  <?php
  ?>
</div>
<script type="text/javascript">
  // Größe des iframes anpassen
  var frame = document.getElementById('converter-frame');
  function adjustIframeSize() {
    var newWidth = document.body.offsetWidth + frame.offsetWidth - document.body.querySelector('table').offsetWidth;
    var newHeight = document.body.offsetHeight + frame.offsetHeight - document.body.querySelector('table').offsetHeight;
    frame.style.width = newWidth;
    frame.style.height = newHeight;
  };
  frame.addEventListener('load', adjustIframeSize, true);
  window.addEventListener('resize', adjustIframeSize, true);

  /*! srcdoc-polyfill - v0.2.0 - 2015-10-02
   * http://github.com/jugglinmike/srcdoc-polyfill/
   * Copyright (c) 2015 Mike Pennisi; Licensed MIT */
//  !function(a,b){var c=window.srcDoc;"function"==typeof define&&define.amd?define(["exports"],function(d){b(d,c),a.srcDoc=d}):"object"==typeof exports?b(exports,c):(a.srcDoc={},b(a.srcDoc,c))}(this,function(a,b){var c,d,e=!!("srcdoc"in document.createElement("iframe")),f={compliant:function(a,b){b&&a.setAttribute("srcdoc",b)},legacy:function(a,b){var c;a&&a.getAttribute&&(b?a.setAttribute("srcdoc",b):b=a.getAttribute("srcdoc"),b&&(c="javascript: window.frameElement.getAttribute('srcdoc');",a.setAttribute("src",c),a.contentWindow&&(a.contentWindow.location=c)))}},g=a;if(g.set=f.compliant,g.noConflict=function(){return window.srcDoc=b,g},!e)for(g.set=f.legacy,d=document.getElementsByTagName("iframe"),c=d.length;c--;)g.set(d[c])});
</script>