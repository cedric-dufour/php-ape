<!-- INDENTING (emacs/vi): -*- mode:html-helper; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab: -->
<TABLE STYLE="WIDTH:100%;" CELLSPACING="0">
  <TR>
    <TD CLASS="label" TITLE="{$Description.username|escape}" STYLE="CURSOR:help;">{$Name.username|escape}:</TD>
    <TD CLASS="input"><INPUT ID="PHP_APE_Auth_username" TYPE="text" {if $Error.username}CLASS="invalid"{else}CLASS="required"{/if} NAME="username" VALUE="{$Value.username|escape}" ONKEYPRESS="javascript:PHP_APE_DR_BasicForm_onKeyPress('{$Global.RID}',event,'insert');"/></TD>
  </TR>
  <TR>
    <TD CLASS="label" TITLE="{$Description.password|escape}" STYLE="CURSOR:help;">{$Name.password|escape}:</TD>
    <TD CLASS="input"><INPUT ID="PHP_APE_Auth_password" TYPE="password" {if $Error.password}CLASS="invalid"{else}CLASS="required"{/if} NAME="password" VALUE="{$Value.password|escape}" ONKEYPRESS="javascript:PHP_APE_DR_BasicForm_onKeyPress('{$Global.RID}',event,'insert');"/></TD>
  </TR>
</TABLE>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--
  document.getElementById({if $Value.username}'PHP_APE_Auth_password'{else}'PHP_APE_Auth_username'{/if}).focus();
--></SCRIPT>
