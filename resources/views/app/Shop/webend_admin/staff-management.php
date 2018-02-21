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
                <div class="iscar_member_icon" from="Shop"></div>
            </div>
        </div>
    </div>

    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="staff-management">
            <div class="page-content animated fadeIn">

                <div class="list-block inset staff-list-block">
                    <ul>


                    </ul>
                </div>

            </div>


            <!-- Floating Action Button -->
            <!--<a href="#" class="floating-button animated zoomIn add">
                +
            </a>-->


            <script type="text/template7" id="templateStaffList">
                {{#each clerk_list}}
                {{#if smb_activestatus}}
                    <li class="item-content">
                        <div class="item-media">
                            <img data-src="{{#if ssd_picturepath}}{{ssd_picturepath}}{{else}}../app/image/general_user.png{{/if}}" width=50 class="lazy" onerror='this.src="../app/image/general_user.png"' />
                        </div>
                        <div class="item-inner">
                            <div class="item-subtitle">
                                <div class="row no-gutter">
                                    <div class="col-85">
                                        <div class="row">
                                            <div class="col-100 title">{{md_cname}}</div>
                                            <div class="col-50">{{duties}}：<span>{{smb_bindlevel}}</span></div>
                                            <div class="col-50"></div>
                                        </div>
                                    </div>
                                    {{#if isEmployee}}
                                    <div class="col-15 isLeaving" smb_md_id="{{smb_md_id}}">
                                        <i class="fa fa-user-times" aria-hidden="true"></i>
                                    </div>
                                    {{/if}}
                                </div>

                            </div>
                        </div>
                    </li>
                    {{/if}}
                {{/each}}
            </script>

            <script type="text/template7" id="templateStaffListNull">
                <div class="content-null">
                    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i></h1>
                    <br>
                    <h3>{{text}}</h3>
                </div>
            </script>


        </div>
    </div>
</div>