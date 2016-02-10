<div style_="width:100%;">
  <h1>XPlanGML Konverter</h1>
  <iframe id="converter-frame" style="width:100%;height:500px;border:0;"
  <?php
    include 'iconvert.php';
    echo "srcdoc=\"".showStep1();
    ?>
  "></iframe>
  <?php
  ?>
</div>
<script type="text/javascript">
  /*! srcdoc-polyfill - v0.2.0 - 2015-10-02
  * http://github.com/jugglinmike/srcdoc-polyfill/
  * Copyright (c) 2015 Mike Pennisi; Licensed MIT */
//  !function(a,b){var c=window.srcDoc;"function"==typeof define&&define.amd?define(["exports"],function(d){b(d,c),a.srcDoc=d}):"object"==typeof exports?b(exports,c):(a.srcDoc={},b(a.srcDoc,c))}(this,function(a,b){var c,d,e=!!("srcdoc"in document.createElement("iframe")),f={compliant:function(a,b){b&&a.setAttribute("srcdoc",b)},legacy:function(a,b){var c;a&&a.getAttribute&&(b?a.setAttribute("srcdoc",b):b=a.getAttribute("srcdoc"),b&&(c="javascript: window.frameElement.getAttribute('srcdoc');",a.setAttribute("src",c),a.contentWindow&&(a.contentWindow.location=c)))}},g=a;if(g.set=f.compliant,g.noConflict=function(){return window.srcDoc=b,g},!e)for(g.set=f.legacy,d=document.getElementsByTagName("iframe"),c=d.length;c--;)g.set(d[c])});
</script>