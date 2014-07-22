<br/>
<hr/>
<?php $pageName = "{$this->page->module}/{$this->page->logicalUnit}/{$this->page->action}"; ?>
<blockquote>
  <p><?= sterm_get('system.www.pagenotfound.title', array($pageName)) ?></p>
  <footer><?= sterm_get('system.www.pagenotfound.description') ?></footer>  
</blockquote>