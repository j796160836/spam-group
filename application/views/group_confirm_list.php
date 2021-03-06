<?php include("_site_header.php") ?>

<div class="container">
	<div class="row">
		<div class="span8 offset2">
			<div class="well">
				<a href="<?=site_url("/")?>" ><h1>Facebook 廣告社團檢查器</h1></a>
				<p> 現在就檢查是否被惡意加入社團！ </p>
				<p><div style="height:60px;" class="fb-like" data-href="http://spamgroup.tonyq.org/" data-send="true" data-width="450" data-show-faces="true"></div></p>
			</div>
			<div class="well">
				<button class="js-admin-auth btn">登入管理員（Tony Wang 限定）</button>
				<br />
				<br />
				審查中社團清單 (總數<?=count($fbgids)?>)
				<table class="table">
					<tr><td>Group ID</td><td>名字</td><td>加入時間</td><Td>+1數量</Td></tr>
					<?php foreach($fbgids as $group){ ?>
						<tr>
							<td><?=$group->GID?></td>
							<td><a href="https://www.facebook.com/groups/<?=htmlspecialchars($group->GID)?>/members/?order=date" target="_blank"><?=htmlspecialchars($group->Name)?></a></td>
							<td><?=$group->CreateDate?></td>
							<td><?=$group->RequestCount?></td>
							<?php if( isset($_SESSION["admin"])  ){?>
							<td> <a class="btn js-confirmed"  href="javascript:void 0;" data-gid="<?=htmlspecialchars($group->GID)?>">通過</a>  </td>
							<?php }?>
						</tr>
					<?php } ?>
				</table>
			</div>
			<div>
					註一：如果這些社團對你而言是正常運作，你不見得一定要退出他們。（但強烈建議取消。）<br />
					註二：本 App 只使用 "取得社團清單權限" 並不會作為任何其他用途，若有疑慮請勿使用。<br />
					註三：如果剛取消社團，但在本系統查詢還在，這是正常的 、FB API 反應比較慢。<Br />
					註四：我們使用的社團清單來源。<a target="_blank" href="https://www.facebook.com/events/315380641913250/permalink/315383471912967/">https://www.facebook.com/events/315380641913250/permalink/315383471912967/</a>
			</div>
			<?php include("_content_nav.php");?>
		</div>
	</div>
</div>
<div id="fb-root"></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
	function fb_init(){
		$(".js-admin-auth").click(function(){
			 FB.login(function(response) {
			   if (response.authResponse) {
				   uid = response.authResponse.userID;
				   $.get("<?=site_url("/group/admin_login")?>?access_token="+FB.getAccessToken(),function(){
					   self.location.reload();
				   });
			   }
			 },{"scope":"user_groups,friends_groups"});
		});

		$(".js-confirmed").click(function(){
			var self = this;
			$.post("<?=site_url("/group/js_confirming")?>",{gid:$(this).data("gid")},function(res){
				var obj = JSON.parse(res);
				if(obj.IsSuccess){
					$(self).text("已審核完成").prop("disabled","disabled");
				}else{
					$(self).text("審核失敗，再試一次");
				}
			});
		});
	}
</script>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '135395566627129', // App ID from the App Dashboard
      channelUrl : '//spam.tonyq.org/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });
	fb_init();
  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might
  // contain some type checks that are overly strict.
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/zh_TW/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));

</script>
<?php include("_site_footer.php"); ?>
