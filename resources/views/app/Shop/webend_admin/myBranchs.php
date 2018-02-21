<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">           
            <div class="left myBranchs-left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding">請先選擇商家</div>
            <div class="right myBranchs-right">
                <div class="iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="myBranchs">
            <div class="page-content animated fadeIn">
                <div class="list-block">
                    <ul class="myBranchsList">

                    </ul>
                </div>
            </div>
            <!-- Floating Action Button -->
            <!-- <a href="#" class="floating-button animated zoomIn open-store">
                +
            </a> -->


            <script type="text/template7" id="templateMyBranchs">
                {{#each shopdata_array}}
                <li class="animated flipInX">
                    <a href="#" onclick="toBranch('{{smb_shoptype}}','{{sd_id}}','{{sd_shopname}}')">
                        <label class="label-radio item-content item-{{sd_id}}">
                            <div class="item-media">
                                <img data-src="{{sd_shopphotopath}}" class="lazy" width=160 onerror='this.src="../app/image/imgDefault.png"' />
                            </div>
                            <div class="item-inner">
                                <div class="item-title">{{sd_shopname}}</div>
                            </div>
                        </label>
                    </a>
                </li>
                {{/each}}
            </script>

        </div>
    </div>