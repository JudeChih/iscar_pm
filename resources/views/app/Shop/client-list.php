<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-popup" data-popup=".shop-menu">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding"></div>
            <div class="right">
                <div class="iscar_member_login iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>
    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="client-list">
            <div class="page-content animated fadeIn">

                <div class="list-block inset client-list-block">
                    <ul>

                    </ul>
                </div>
            </div>


            <script type="text/template7" id="templateClientList">
            {{#each shop_member_array}}
            <li class="item-content list-item swipeout">
                <div class="swipeout-content item-content">
                    <div class="item-media">
                        <img data-src="{{#if ssd_picturepath}}{{ssd_picturepath}}{{else}}http://125.227.129.115/app/user_icon/general_user.png{{/if}}" width=50 class="lazy" onerror='this.src="http://125.227.129.115/app/user_icon/general_user.png"' />
                    </div>
                    <a href="#" class="item-info">
                        <span class="title">{{md_cname}}</span>
                    </a>
                </div>
                <!--<div class="swipeout-actions-right">
                    {{#if isAssign}}
                    <a href="#" class="assign" cmr-id="{{cmr_id}}"><i class="fa fa-star-half-o" aria-hidden="true"></i></a>
                    {{/if}}
                    {{#if isExpel}}
                    <a href="#" class="expel" cmr-id="{{cmr_id}}"><i class="fa fa-user-times" aria-hidden="true"></i></a>
                    {{/if}}
                </div>-->
            </li>
            {{/each}}
            </script>

            <script type="text/template7" id="templateClientListNull">
            <div class="content-null">
                <h1><i class="fa fa-users" aria-hidden="true"></i></h1>
                <br>
                <h3>{{text}}</h3>
            </div>
            </script>


        </div>
    </div>
</div>