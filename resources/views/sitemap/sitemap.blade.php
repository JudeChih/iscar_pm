<?php echo '<?xml-stylesheet type="text/xsl" href="/sitemap/xml-sitemap.xsl"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($coupondata as $value)
<url>
    <loc>{{$otherdata['coupon_url']}}?scm_id={{$value['scm_id']}}</loc>
    <lastmod>{{$value['last_update_date']}}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.6</priority>
</url>
@endforeach
@foreach ($shopdata as $value)
<url>
    <loc>{{$otherdata['shop_url']}}?sd_id={{$value['sd_id']}}</loc>
    <lastmod>{{$value['last_update_date']}}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.6</priority>
</url>
@endforeach
</urlset>
