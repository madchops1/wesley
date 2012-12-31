<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <form name="cssform" id="cssform">
      <table class="general" id="css">
        <tr>
          <td class="tabs">
            <xsl:for-each select="css/collections/collection">
              <a href="#" class="tab" onClick="showdiv(this.innerHTML)">
                <xsl:value-of select="@name"/>
              </a>
              <span class="space"></span>
            </xsl:for-each>
          </td>
        </tr>
        <tr>
          <td>
            <xsl:for-each select="css/collections/collection">
              <div id="" class="group" name="cssdiv">
                <xsl:attribute name="id">
                  <xsl:value-of select="@name"/>
                </xsl:attribute>
                <xsl:apply-templates />
              </div>
            </xsl:for-each>
          </td>
        </tr>
        <tr>
          <td>
            <fieldset>
              <legend>Preview</legend>
              <div id="demo">CSS Element</div>
            </fieldset>
          </td>
        </tr>
      </table>
    </form>
  </xsl:template>

  <xsl:template match="group">
    <fieldset title="">
      <xsl:attribute name="title">
        <xsl:value-of select="@name"/>
      </xsl:attribute>
      <legend>
        <xsl:value-of select="@name"/>
      </legend>
      <table class="group" id="">
        <xsl:attribute name="id">
          <xsl:value-of select="@name"/>
        </xsl:attribute>

        <xsl:for-each select="element">
          <tr>
            <td class="element">
              <xsl:value-of select="@name"/>
            </td>
            <td class="entries">
              <xsl:apply-templates />
            </td>
          </tr>
        </xsl:for-each>
      </table>
    </fieldset>
  </xsl:template>

  <xsl:template match="entries">
    <select class="entry" id="" name="" onchange="if(!checkSpecial(this)) changeStyle(this);">
      <xsl:attribute name="id">
        <xsl:value-of select="../@name"/>
      </xsl:attribute>
      <xsl:attribute name="name">
        <xsl:value-of select="../@name"/>
      </xsl:attribute>
      <option></option>
      <xsl:for-each select="entry">
        <option value="">
          <xsl:attribute name="value">
            <xsl:value-of select="."/>
          </xsl:attribute>
          <xsl:value-of select="."/>
        </option>
      </xsl:for-each>
      <xsl:for-each select="list">

        <xsl:variable name="attr">
          <xsl:value-of select="."/>
        </xsl:variable>

        <xsl:for-each select="/css/entry-lists/entry-list[@name=$attr]">
          <xsl:for-each select="item">
            <option value="">
              <xsl:attribute name="value">
                <xsl:value-of select="."/>
              </xsl:attribute>
              <xsl:value-of select="."/>
            </option>
          </xsl:for-each>
        </xsl:for-each>
      </xsl:for-each>
    </select>
  </xsl:template>

</xsl:stylesheet>
