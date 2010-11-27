<?xml version="1.0" encoding="utf-8"?> 
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">
 <channel>
  <title>Cameron&apos;s Thoughts</title> 
  <link>http://www.cbulock.com/</link> 
  <description>Why would you want to know what I&apos;m thinking? Weirdos.</description>
  <image> 
   <url>http://www.cbulock.com/cameron.jpg</url> 
   <title>Cameron</title> 
   <link>http://www.cbulock.com/</link> 
   <width>87</width> 
   <height>95</height> 
  </image>
  {foreach from=$entries item=entry}
  <item>
   <title>{$entry.entry_title}</title>
   <link>http://www.cbulock.com{$entry.entry_link}</link>
   <guid isPermaLink="false">{$entry.entry_id}@http://www.cbulock.com/</guid>
   <description>{$entry.entry_excerpt}</description>
   <content:encoded><![CDATA[<p>{$entry.entry_text}</p>]]></content:encoded>
  </item>
  {/foreach}
 </channel>
</rss>
