<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <link rel="stylesheet" type="text/css" href="./style.css" />
      </head>
      <body>
        <h2>Employee Details</h2>
        <table border="1">
          <tr bgcolor="#9acd32">
            <th>Name</th>
            <th>Home Phone</th>
            <th>Work Phone</th>
            <th>Mobile Phone</th>
            <th>Street</th>
            <th>Building Number</th>
            <th>Region</th>
            <th>City</th>
            <th>Country</th>
            <th>Email</th>
          </tr>
          <xsl:for-each select="employees/employee">
            <tr>
              <td>
                <xsl:value-of select="name" />
              </td>
              <td>
                <xsl:value-of select="phones/phone[@type='home']" />
              </td>
              <td>
                <xsl:value-of select="phones/phone[@type='work']" />
              </td>
              <td>
                <xsl:value-of select="phones/phone[@type='mobile']" />
              </td>
              <td>
                <xsl:value-of select="addresses/address/Street" />
              </td>
              <td>
                <xsl:value-of select="addresses/address/BuildingNumber" />
              </td>
              <td>
                <xsl:value-of select="addresses/address/Region" />
              </td>
              <td>
                <xsl:value-of select="addresses/address/City" />
              </td>
              <td>
                <xsl:value-of select="addresses/address/Country" />
              </td>
              <td>
                <xsl:value-of select="email" />
              </td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
