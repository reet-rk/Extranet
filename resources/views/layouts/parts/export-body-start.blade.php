@if ($format === 'pdf')
<style media="print">
    .print-header-footer {
        position: fixed;
        width: 100%;
    }
    .print-footer {
        position: fixed;
        bottom: -40px;
        width: 100%;
    }
    .print-header-footer-inner {
        max-width: 840px;
        margin: 0 auto;
        color: #666;
    }
    .print-page-number:after {
        content: "Page "counter(page);
    }
    @page {
        margin-top: 100px;
        margin-bottom: 80px;
    }
</style>

<div class="print-header-footer" style="top: -60px;">
    <div class="print-header-footer-inner">
        <div style="float: left; opacity: 0.6;">
           <img height="42" src="data:image/png;base64,{{ base64_encode(file_get_contents(theme_path('LogoEYOFSK.png'))) }}">
           
        </div>
        <div style="float: right;">
            <div class="print-page-number"></div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>


@endif
