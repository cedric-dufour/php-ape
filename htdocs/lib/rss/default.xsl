<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html"/>
<xsl:template match="/rss/channel">
<HTML>
<HEAD>
<TITLE><xsl:value-of select="title"/></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1"/>
<STYLE TYPE="text/css">
BODY {MARGIN:10px;WIDTH:100%;PADDING:0px;FONT:normal 11px Arial,Helvetica,sans-serif;BACKGROUND:#FFFFFF;COLOR:#000000;SCROLLBAR-TRACK-COLOR:#F0F0F0;SCROLLBAR-BASE-COLOR:#B0B0B0;}
H1 {MARGIN:0px;PADDING:0px;FONT:bold 14px "Century Gothic",sans-serif;COLOR:#000000;WHITE-SPACE:nowrap;}
H2 {MARGIN:5px 0px;PADDING:0px;FONT:12px "Century Gothic",sans-serif;COLOR:#000000;WHITE-SPACE:nowrap;}
P {MARGIN:0px;PADDING:0px;FONT:11px Arial,Helvetica,sans-serif;COLOR:#000000;}
A {TEXT-DECORATION:underline;BACKGROUND:transparent;COLOR:#303030;}
A:hover {TEXT-DECORATION:underline;BACKGROUND:transparent;COLOR:#909090;}
IMG {MARGIN:0px;PADDING:0px;BORDER:none;}
DIV.header {}
DIV.header DIV.title {}
DIV.header DIV.image {PADDING:5px;BACKGROUND:#F7F7F7;BORDER:solid 1px #B0B0B0}
DIV.header DIV.description {MARGIN:10px;}
DIV.header DIV.separator {WIDTH:100%;HEIGHT:1px;FONT-SIZE:1px;BORDER-BOTTOM:solid 1px #404040;}
DIV.item {}
DIV.item DIV.title {}
DIV.item DIV.description {MARGIN:10px;}
DIV.item DIV.separator {WIDTH:100%;HEIGHT:1px;FONT-SIZE:1px;BORDER-BOTTOM:dashed 1px #404040;}
</STYLE>
</HEAD>
<BODY>
<DIV CLASS="header">
<DIV CLASS="title">
<xsl:choose>
<xsl:when test="link">
<H1><A HREF="{link}"><xsl:value-of select="title"/></A></H1>
</xsl:when>
<xsl:otherwise>
<H1><xsl:value-of select="title"/></H1>
</xsl:otherwise>
</xsl:choose>
</DIV>
<xsl:for-each select="image">
<DIV CLASS="image">
<xsl:choose>
<xsl:when test="link">
<A HREF="{link}" TITLE="{title}"><IMG SRC="{url}" ALT="{title}"/></A>
</xsl:when>
<xsl:otherwise>
<IMG SRC="{url}" ALT="{title}"/>
</xsl:otherwise>
</xsl:choose>
</DIV>
</xsl:for-each>
<DIV CLASS="description">
<P><xsl:value-of select="description"/></P>
</DIV>
<DIV CLASS="separator"></DIV>
</DIV>
<DIV CLASS="item">
<xsl:for-each select="item">
<DIV CLASS="title">
<xsl:choose>
<xsl:when test="link">
<H2><A HREF="{link}"><xsl:value-of select="title"/></A></H2>
</xsl:when>
<xsl:otherwise>
<H2><xsl:value-of select="title"/></H2>
</xsl:otherwise>
</xsl:choose>
</DIV>
<DIV CLASS="description">
<P><xsl:value-of select="description" disable-output-escaping="yes"/></P>
</DIV>
<DIV CLASS="separator"></DIV>
</xsl:for-each>
</DIV>
</BODY>
</HTML>
</xsl:template>
</xsl:stylesheet>
