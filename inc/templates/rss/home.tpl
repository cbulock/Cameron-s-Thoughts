<?xml version="1.0" encoding="UTF-8"?> 
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">
 <channel>
  <title>{$site_name}</title> 
  <link>http://www.cbulock.com/</link>
  <atom:link href="http://www.cbulock.com/rss" rel="self" type="application/rss+xml" />
  <description>{$site_tagline}</description>
  <image> 
   <url>http://www.cbulock.com/cameron.jpg</url> 
   <title>{$site_name}</title> 
   <link>http://www.cbulock.com/</link> 
   <width>87</width> 
   <height>95</height> 
  </image>
  {foreach from=$entries item=entry}
  <item>
   <title>{$entry.entry_title}</title>
   <link>http://www.cbulock.com{$entry.entry_link}</link>
   <guid isPermaLink="false">{$entry.entry_id}@http://www.cbulock.com/</guid>
   <description>{$entry.entry_excerpt|escape:'htmlall'}</description>
   <content:encoded><![CDATA[<p>{$entry.entry_text}</p>]]></content:encoded>
  </item>
  {/foreach}
 </channel>
</rss>
