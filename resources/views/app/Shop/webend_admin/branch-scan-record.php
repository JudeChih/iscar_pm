<div class="view view-main">
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left">
                <a href="#" class="link icon-only open-panel">
                    <span class="kkicon icon-menu"></span>
                </a>
            </div>
            <div class="center sliding bonus-inquire-title">掃描記錄</div>
            <div class="right">
                <a href="#" class="link icon-only open-login-screen">
                    <span class="kkicon icon-user"></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Pages -->
    <div class="pages">
        <div class="page" data-page="branch-scan-record">

            <div class="page-content animated fadeIn">

                <div class="sever-subTitle date1">
                </div>
                <div class="record-list date1-block">
                
                </div>

                <div class="sever-subTitle date2">
                </div>
                <div class="record-list date2-block">

                </div>


                <div class="sever-subTitle date3">
                </div>

                <div class="record-list date3-block">

                </div>

            </div>

            <!--template7-->
            <script type="text/template7" id="templateBranchScanRecordItem">
                <div class="card" {{#if scm_balanceno}}onclick="code39('{{scm_balanceno}}')"{{/if}}>
                    <div class="card-header">{{scm_title}}</div>
                    <div class="card-content">
                        <div class="card-content-inner">

                            <div class="row">
                                <span class="col-60"><span class="note">活動期限：</span>{{scm_enddate}}</span>
                                <span class="col-40"><span class="note">收取數：</span>{{scanNum}}</span>
                            </div>

                            {{#if scm_balanceno}}<span><span class="note">銷帳條碼：</span>{{scm_balanceno}}</span>{{/if}}

                        </div>
                    </div>
                </div>
            </script>

            <script type="text/template7" id="templateBranchScanRecordNull">
                <div class="content-null">
                    <h1><i class="fa fa-search"></i></h1>
                    <br>
                    <h3>無掃描記錄</h3>
                </div>
            </script>

        </div>
    </div>
</div>