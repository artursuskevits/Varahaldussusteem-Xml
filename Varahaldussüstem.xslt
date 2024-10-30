<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" encoding="UTF-8" indent="yes"/>

	<xsl:key name="sortByDate" match="vara" use="details/lisaaeg"/>

	<!-- Main template to transform the XML -->
	<xsl:template match="/varad">
		<html>
			<head>
				<title>Varad Information</title>
				<style>
					table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
					th, td { border: 1px solid black; padding: 8px; text-align: left; }
					th { background-color: #f2f2f2; }
				</style>
			</head>
			<body>
				<h2>Varad Full List</h2>
				<!-- Full list of varad -->
				<table>
					<tr>
						<th>Varanumber</th>
						<th>Nimetus</th>
						<th>Seisund</th>
						<th>Maksmus</th>
						<th>Vastutaja</th>
						<th>Lisa Aeg</th>
					</tr>
					<xsl:for-each select="vara">
						<tr>
							<td>
								<xsl:value-of select="details/varanumber"/>
							</td>
							<td>
								<xsl:value-of select="details/nimetus"/>
							</td>
							<td>
								<xsl:value-of select="details/seisund"/>
							</td>
							<td>
								<xsl:value-of select="details/maksmus"/>
							</td>
							<td>
								<xsl:value-of select="details/vastutaja"/>
							</td>
							<td>
								<xsl:value-of select="details/lisaaeg"/>
							</td>
						</tr>
					</xsl:for-each>
				</table>

				<h2>Varad with Seisund = Uus</h2>
				<!-- Table showing only Seisund = Uus -->
				<table>
					<tr>
						<th>Varanumber</th>
						<th>Nimetus</th>
						<th>Seisund</th>
						<th>Maksmus</th>
						<th>Vastutaja</th>
						<th>Lisa Aeegg</th>
					</tr>
					<xsl:for-each select="vara[details/seisund='Uus']">
						<tr>
							<td>
								<xsl:value-of select="details/varanumber"/>
							</td>
							<td>
								<xsl:value-of select="details/nimetus"/>
							</td>
							<td>
								<xsl:value-of select="details/seisund"/>
							</td>
							<td>
								<xsl:value-of select="details/maksmus"/>
							</td>
							<td>
								<xsl:value-of select="details/vastutaja"/>
							</td>
							<td>
								<xsl:value-of select="details/lisaaeg"/>
							</td>
						</tr>
					</xsl:for-each>
				</table>

				<h2>Varad with Seisund != Uus</h2>
				<!-- Table showing only Seisund != Uus -->
				<table>
					<tr>
						<th>Varanumber</th>
						<th>Nimetus</th>
						<th>Seisund</th>
						<th>Maksmus</th>
						<th>Vastutaja</th>
						<th>Lisa Aeg</th>
					</tr>
					<xsl:for-each select="vara[details/seisund!='Uus']">
						<tr>
							<td>
								<xsl:value-of select="details/varanumber"/>
							</td>
							<td>
								<xsl:value-of select="details/nimetus"/>
							</td>
							<td>
								<xsl:value-of select="details/seisund"/>
							</td>
							<td>
								<xsl:value-of select="details/maksmus"/>
							</td>
							<td>
								<xsl:value-of select="details/vastutaja"/>
							</td>
							<td>
								<xsl:value-of select="details/lisaaeg"/>
							</td>
						</tr>
					</xsl:for-each>
				</table>

				<h2>5 Oldest Varad</h2>
				<!-- Table showing 5 oldest items based on lisaaeg -->
				<table>
					<tr>
						<th>Varanumber</th>
						<th>Nimetus</th>
						<th>Seisund</th>
						<th>Maksmus</th>
						<th>Vastutaja</th>
						<th>Lisa Aeg</th>
					</tr>
					<xsl:for-each select="vara">
						<xsl:sort select="details/lisaaeg" order="ascending"/>
						<xsl:if test="position() &lt;= 5">
							<tr>
								<td>
									<xsl:value-of select="details/varanumber"/>
								</td>
								<td>
									<xsl:value-of select="details/nimetus"/>
								</td>
								<td>
									<xsl:value-of select="details/seisund"/>
								</td>
								<td>
									<xsl:value-of select="details/maksmus"/>
								</td>
								<td>
									<xsl:value-of select="details/vastutaja"/>
								</td>
								<td>
									<xsl:value-of select="details/lisaaeg"/>
								</td>
							</tr>
						</xsl:if>
					</xsl:for-each>
				</table>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
