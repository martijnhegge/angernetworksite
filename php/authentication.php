<?php
	include "database.php";


	class auth extends database{
		public function __construct(){
			$this->connect();
			session_start();
		}
		public function referralCheck(){
			$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$refid = $this->generateRandomString().$this->generateRandomString();
				$this->insert_query("referralid", array("userid"=>$_SESSION['id'],"refid"=>$refid));
			}
		}
		public function storeCheck(){
			$query = $this->db->prepare("SELECT * FROM `store` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$this->insert_query("store", array("userid"=>$_SESSION['id']));
			}
		}
		public function levelCheck(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result['level'] == ""){
				$this->update("users", array("level"=>"User"), "id", $_SESSION['id']);
			}
		}//spin_for_credits_timeout
		public function spinForCreditsCheck(){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result['spin_for_credits_timeout'] == ""){
				$date = strtotime(date('Y-m-d h:i:s') . ' -5 days');
				$this->update("users", array("spin_for_credits_timeout"=>date('Y-m-d h:i:s', $date)), "id", $_SESSION['id']);
			}
		}
		public function menuSettingCheck(){
			$query = $this->db->prepare("SELECT * FROM `menu_settings` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$this->insert_query("menu_settings", array("userid"=>$_SESSION['id'], "guiR"=>"104","guiG"=>"7","guiB"=>"202"));
			}
		}
		public function ipCheck(){
			$this->update("users", array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']), "id", $_SESSION['id']);
		}
		public function getUserLoggedIn()
		{
			$query = $this->db->prepare("UPDATE `users` SET `active` = :active WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result['active'])
			{
				
                $this->update("users", array("OnlineStatus"=>"Online","OnlineStatusColor"=>"success","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']),"id", $_SESSION['id']);
			}
		}
		public function getUserLoginLogs()
	    {
	         $query = $this->db->prepare("UPDATE `active_users` SET `active` = :active WHERE `userid` = :id AND `username` = :username  ORDER BY `lastactivity`DESC");
	         $query->execute(array("id"=>$_SESSION['id']));
	         $result = $query->fetch(PDO::FETCH_ASSOC);
	         if(!$result)
	         {
	         // $date = strtotime(date('Y-m-d h:i:s'));
	         //"last_date"=>date('Y-m-d',
	          $this->update("active_users",array("username"=>$_SESSION['username'],"active"=>"1","latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']),"userid", $_SESSION['id']);
	         }
	    }
	    public function BlacklistCheck(){
			$query = $this->db->prepare("SELECT * FROM `black_list` WHERE `userid` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$this->insert_query("black_list",array("userid" => $_SESSION['id'] = $this->getUserId(self::sanitize($user)),
                    "username"=> $this->getUserName(self::sanitize($user)),
                    "ip_address"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
			}
		}
		public function getUserBrowserLogs()
	    {
	        $query = $this->db->prepare("UPDATE `login_logs_users` WHERE `userid` = :id AND `username` = :username  ORDER BY `time`DESC");
	        $query->execute(array("id"=>$_SESSION['id']));
	        $result = $query->fetch(PDO::FETCH_ASSOC);
	        if(!$result)
	        {  
	            $this->update("login_logs_users",
	            array("username"=>$_SESSION['username'],
	            "ip"=>$this->getUserIpAddress(),
                "country"=>$this->getGeoCountry(),
                "city"=>$this->getGeoCity(),
			    "isp"=>$this->getGeoISP(),
			    "browser"=>$this->getTheBrowser(),
                "user_agent"=>$this->getUserAgent()),               
	            "userid", $_SESSION['id']);
	        }
	    }
		public function initChecks(){
			$this->getUserBrowserLogs();
			$this->getUserLoginLogs();
			$this->getUserLoggedIn();
			$this->referralCheck();
			$this->storeCheck();
			$this->levelCheck();
			$this->spinForCreditsCheck();
			$this->ipCheck();
			$this->menuSettingCheck();
			//$this->getCurrentLogin();
		}
		public function outChecks(){
			$this->getUserLoggedOutCheck();
			//$this->membershipCheck();
		}
        public function membershipCheck($id)
	    {
		   $query = $this->db->prepare("SELECT `expiry_date` FROM `users` WHERE `id` = :id");
		   $query->execute(array("id"=>$_SESSION['id']));
		   $expiry_date = $query->fetch(PDO::FETCH_ASSOC);
		   if (time() < $expiry_date)
		   {
			return true;
		   }
		   else
		   {
		    $query = $this->db->prepare("UPDATE `users` SET `active` = 0 WHERE `id` = :id");
			$query->execute(array("id"=>$_SESSION['id']));
			return false;
		   }
	    }  
	    public function BrutforceCheck($user)
		{	$bruteforcetext = "There are bruteforcing attempts with ip ".$this->getUserIpAddress()." and username ".$result['username'];
		    
			//$isbruteforcing = 'You seems like a brute-forcer. Just wait a few minutes before trying again if you are not.';

			$querybf = $this->db->prepare("SELECT COUNT(attempts) FROM `bruteforcelogs` WHERE `ip` = :ip AND `username` = :username AND `time` > DATE_SUB(NOW(), INTERVAL 8 MINUTE)");
		    $querybf->execute(array("ip"=>$this->getUserIpAddress(), "username"=>self::sanitize($user)));
		    $result = $querybf->fetch(PDO::FETCH_ASSOC);
	        if ($result > 5)
	        {
			    sleep(2);
			    return "bruteforcing";
			}
			else 
			{  
			    $this->insert_query("bruteforcelogs", 
			    array("id"=>$result['id'],
			    "ip"=>$this->getUserIpAddress(),
			    "username"=> $this->getUserName(self::sanitize($user)),
			    "attempts"=>1, "comment"=>$bruteforcetext));
			}      		    	   
		}
		public function login($user,$pass){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				if($result['verified'] == 0)
				{
					return "not-verified";
				}
				
				if(password_verify($pass,$result['password'])){
				$query = $this->db->prepare("SELECT * FROM `bans` WHERE `userid` = :id");
				$query->execute(array("id"=>self::sanitize($result['id'])));
				$res = $query->fetch(PDO::FETCH_ASSOC);
				if($res)
				{
					$_SESSION['id'] = $result['id'];
					if($res['type'] == "Temp")
					{
						if(time() > $res['unban_when'])
						{
							$query = $this->db->prepare("DELETE FROM `bans` WHERE `userid` = :id");
							$query->execute(array("id"=>self::sanitize($result['id'])));
							$_SESSION['id'] = $result['id'];
							$this->initChecks();
						}
						else
						{
							$_SESSION['id'] = $result['id'];
							$_SESSION['username'] = $result['username'];
							return "timeout";
						}
					}
					else
					{
						$_SESSION['id'] = $result['id'];
						$_SESSION['username'] = $result['username'];
						return "banned";
					}
				}
				else
				{
					$dateStamp = $_SERVER['REQUEST_TIME'];
					//$date = date("Y-m-d H:i:s");
                    //$datetime = date("Y-m-d H:i:s");
					$_SESSION['id'] = $result['id'];
					$_SESSION['time'] = $result['id'];
					$_SESSION['username'] = $result['username'];
		            $this->initChecks();
	                   
					    $query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					    $query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					    $result = $query->fetch(PDO::FETCH_ASSOC);
					    $passwordlength = strlen($pass);
						if($result['ipaddress'] >= 1){
							return "ipbanned";
							}
							else if ($passwordlength < 6)
							{
							return "passnotlong";
						}
						else
					    {
					       $this->insert_query("login_logs",
			                array("userid"=> $this->getUserId(self::sanitize($user)),
			                "username"=> $this->getUserName(self::sanitize($user)),
			                "ip"=>$this->getUserIpAddress(),
			                "country"=>$this->getGeoCountry(),
			                "city"=>$this->getGeoCity(),
			                "isp"=>$this->getGeoISP(),
			                "browser"=>$this->getTheBrowser(),
			                "user_agent"=>$this->getUserAgent()));
						   return "success";
					    }
				    }
			    }
			    else
			    {
                return "no-exist";
			    }
		    }
			else
			{
              return "no-exist";
			}
		}
		public function getUserId($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['id'];
			else
				return null;
		}
		public function getUserEmail($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['email'];
			else
				return null;
		}
	    public function getUserName($name){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($name)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
				return $result['username'];
			else
				return null;
		}
		public function register($user,$pass,$repass,$email)
		{
			$spamEmails = array('.cn', '.ru','.tk', '0-mail.com', '0815.ru', '0815.ru0clickemail.com', '0815.su', '0clickemail.com', '0wnd.net', '0wnd.org', '10minut.com.pl', '10minutemail.cf', '10minutemail.co.za', '10minutemail.com', '10minutemail.com ', '10minutemail.de', '10minutemail.ga', '10minutemail.gq', '10minutemail.ml', '10minutemail.net', '123-m.com', '126.com', '12houremail.com', '12minutemail.com', '139.com', '163.com', '1ce.us', '1chuan.com', '1mail.ml', '1pad.de', '1zhuan.com', '20mail.it', '20minutemail.com', '21cn.com', '24hourmail.com', '2prong.com', '30minutemail.com', '30minutenmail.eu', '33mail.com', '3d-painting.com', '3mail.ga', '4mail.cf', '4mail.ga', '4warding.com', '4warding.net', '4warding.org', '5mail.cf', '5mail.ga', '5ymail.com', '60-minuten-mail.de', '60minutemail.com', '675hosting.com', '675hosting.net', '675hosting.org', '6hjgjhgkilkj.tk', '6ip.us', '6mail.cf', '6mail.ga', '6mail.ml', '6paq.com', '6url.com', '75hosting.com', '75hosting.net', '75hosting.org', '7days-printing.com', '7mail.ga', '7mail.ml', '7tags.com','8mail.cf', '8mail.ga', '8mail.ml', '99experts.com', '9mail.cf', '9ox.net', 'LMAO.com', 'PutThisInYourSpamDatabase.com', 'STFU.com', 'SendSpamHere.com', 'SpamHereLots.com', 'SpamHerePlease.com', 'TempEMail.net', 'a-bc.net', 'a45.in', 'abcmail.email', 'aboutmichelle', 'aboutmichelle.net', 'aboutmichellekelly.com', 'aboutmichellekely-d0c.com', 'abusemail.de', 'abyssmail.com', 'acentri.com', 'adam.com', 'adamhoward.com', 'address.com', 'adresseemailtemporaire.com', 'advantimo.com', 'afrobacon.com', 'ag.us.to', 'agedmail.com', 'ahalfassbloggingandforum.website', 'ahk.jp', 'ajaxapp.net', 'akapost.com', 'akerd.com', 'alivance.com', 'ama-trade.de', 'ama-trans.de', 'amail.com', 'amilegit.com', 'amiri.net', 'amiriindustries.com', 'amuweb.com', 'anappthat.com', 'ano-mail.net', 'anonbox.net', 'anonmails.de', 'anonymail.dk', 'anonymbox.com', 'anonymous-email.net', 'anonymousfeedback.net', 'anonymousmail.org', 'anonymousspeech.com', 'antichef.com', 'antichef.net', 'antireg.com', 'antireg.ru', 'antispam.com', 'antispam.de','antispam.sergeychernyshev.com', 'antispam.stanger.org', 'antispammail.de', 'appixie.com', 'aprispamail.it', 'armyspy.com', 'artman-conception.com', 'asdasd.ru', 'ass.com', 'aver.com', 'azmeil.tk', 'b2cmail.de', 'bacohe.com', 'baxomale.ht.cx', 'beddly.com', 'beefmilk.com', 'bemaxi.com', 'bespamed.com', 'big1.us', 'bigprofessor.so', 'bigstring.com', 'binkmail.com', 'bio-muesli.info', 'bio-muesli.net', 'blackmarket.to', 'bladesmail.net', 'bloatbox.com', 'blogmyway.org', 'blogos.com', 'bluebottle.com', 'bluewin.ch', 'bobmail.info', 'bodhi.lawlita.com', 'bofthew.com', 'bootybay.de', 'boun.cr', 'bouncr.com', 'box.spam.futz.org', 'boxformail.in', 'boximail.com', 'brainonfire.net', 'breakthru.com', 'brefmail.com', 'brennendesreich.de', 'broadbandninja.com', 'bsnow.net', 'bspamfree.org', 'bu.mintemail.com', 'buffemail.com', 'bugmenot.com', 'bumpymail.com', 'bund.us', 'bundes-li.ga', 'burnthespam.info', 'burstmail.info', 'bustspammers.com', 'buymoreplays.com', 'buyusedlibrarybooks.org', 'buzstat.com', 'byom.de','c2.hu', 'cachedot.net', 'cailune.com', 'cam4you.cc', 'card.zp.ua', 'casualdx.com', 'cbair.com', 'cek.pm', 'cellurl.com', 'centermail.com', 'centermail.net', 'chammy.info', 'cheatmail.de', 'checknew.pw', 'childsavetrust.org', 'chogmail.com', 'choicemail1.com', 'chong-mail.com', 'chong-mail.net', 'chong-mail.org', 'clixser.com', 'clrmail.com', 'cmail.com', 'cmail.net', 'cmail.org', 'coldemail.info', 'consumerriot.com', 'cookiecooker.de', 'cool.fr.nf', 'correo.blogos.net', 'cosmorph.com', 'courriel.fr.nf', 'courrieltemporaire.com', 'crapmail.org', 'crazymailing.com', 'cubiclink.com', 'curryworld.de', 'cust.in', 'cuvox.de', 'd3p.dk', 'dacoolest.com', 'daintly.com', 'dandikmail.com', 'dapoci.com', 'dayrep.com', 'dbunker.com', 'dcemail.com', 'deadaddress.com', 'deadchildren.org', 'deadfake.cf', 'deadfake.ga', 'deadfake.ml', 'deadfake.tk', 'deadspam.com', 'deagot.com', 'dealja.com', 'delikkt.de', 'dermaspamed.com', 'despam.it', 'despammed.com', 'devnullmail.com', 'dfgh.net', 'dharmatel.net', 'didotube.com','digitalsanctuary.com', 'dingbone.com', 'discard.cf', 'discard.email', 'discard.ga', 'discard.gq', 'discard.ml', 'discard.tk', 'discardmail.com', 'discardmail.de', 'disposable-email.ml', 'disposable.cf', 'disposable.ga', 'disposable.ml', 'disposableaddress.com', 'disposableemailaddresses.com', 'disposableemailaddresses.emailmiser.com', 'disposableinbox.com', 'dispose.it', 'disposeamail.com', 'disposemail.com', 'dispostable.com', 'divermail.com', 'dm.w3internet.co.uk', 'dm.w3internet.co.ukexample.com', 'docmail.com', 'dodgeit.com', 'dodgemail.de', 'dodgit.com', 'dodgit.org', 'doiea.com', 'domozmail.com', 'donemail.ru', 'dont-spam-me.de', 'dontreg.com', 'dontsendmespam.de', 'dotmsg.com', 'drdrb.com', 'drdrb.net', 'dropcake.de', 'droplar.com', 'droplister.com', 'duam.net', 'dudmail.com', 'dump-email.info', 'dumpandjunk.com', 'dumpmail.de', 'dumpyemail.com', 'duskmail.com', 'e-mail.com', 'e-mail.org', 'e-postkasten.com', 'e-postkasten.de', 'e-postkasten.eu', 'e-postkasten.info', 'e4ward.com', 'easytrashmail.com','eatspam.co.uk', 'edv.to', 'ee1.pl', 'ee2.pl', 'eelmail.com', 'einfach.to', 'einmalmail.de', 'einrot.com', 'einrot.de', 'eintagsmail.de', 'email-fake.cf', 'email-fake.com', 'email-fake.ga', 'email-fake.gq', 'email-fake.ml', 'email-fake.tk', 'email60.com', 'emailage.cf', 'emailage.ga', 'emailage.gq', 'emailage.ml', 'emailage.tk', 'emaildienst.de', 'emailgo.de', 'emailias.com', 'emailigo.de', 'emailinfive.com', 'emaillime.com', 'emailmiser.com', 'emailproxsy.com', 'emails.ga', 'emailsensei.com', 'emailspam.cf', 'emailspam.ga', 'emailspam.gq', 'emailspam.ml', 'emailspam.tk', 'emailtemporanea.com', 'emailtemporanea.net', 'emailtemporar.ro', 'emailtemporario.com.br', 'emailthe.net', 'emailtmp.com', 'emailto.de', 'emailwarden.com', 'emailx.at.hm', 'emailxfer.com', 'emailyoyo.com', 'emailz.cf', 'emailz.ga', 'emailz.gq', 'emailz.ml', 'emeil.in', 'emeil.ir', 'emil.com', 'emkei.cf', 'emkei.ga', 'emkei.gq', 'emkei.ml', 'emkei.tk', 'empiremail.de', 'emz.net', 'entclips.com', 'enterto.com', 'ephemail.net', 'ero-tube.org','etranquil.com', 'etranquil.net', 'etranquil.org', 'evopo.com', 'example.com', 'explodemail.com', 'express.net.ua', 'eyepaste.com', 'facebook-email.cf', 'facebook-email.ga', 'facebook-email.ml', 'facebook.com', 'facebookmail.gq', 'facebookmail.ml', 'faecesmail.me', 'fake-box.com', 'fake-mail.cf', 'fake-mail.ga', 'fake-mail.ml', 'fakedemail.com', 'fakeinbox.cf', 'fakeinbox.com', 'fakeinbox.ga', 'fakeinbox.ml', 'fakeinbox.tk', 'fakeinformation.com', 'fakemail.fr', 'fakemailgenerator.com', 'fakemailz.com', 'fammix.com', 'fansworldwide.de', 'fantasymail.de', 'fastacura.com', 'fastchevy.com', 'fastchrysler.com', 'fastermail.com', 'fastkawasaki.com', 'fastmazda.com', 'fastmitsubishi.com', 'fastnissan.com', 'fastsubaru.com', 'fastsuzuki.com', 'fasttoyota.com', 'fastyamaha.com', 'fatflap.com', 'fdfdsfds.com', 'fightallspam.com', 'fiifke.de', 'film-blog.biz', 'filzmail.com', 'fivemail.de', 'fixmail.tk', 'fizmail.com', 'fleckens.hu', 'flitafir.de', 'flurred.com', 'fly-ts.de', 'flyspam.com', 'footard.com', 'forgetmail.com', 'fornow.eu', 'fr33mail.info', 'frapmail.com', 'free-email.cf', 'free-email.ga', 'freecoolemail.com', 'freemail.ms', 'freemails.cf', 'freemails.ga', 'freemails.ml', 'freundin.ru', 'friendlymail.co.uk', 'front14.org', 'fu.com', 'fuck.com', 'fuckingduh.com', 'fuckyou.com', 'fudgerub.com', 'fux0ringduh.com', 'fyii.de', 'garbagemail.org', 'garliclife.com', 'gawab.com', 'gehensiemirnichtaufdensack.de', 'gelitik.in', 'geschent.biz', 'get-mail.cf', 'get-mail.ga', 'get-mail.ml', 'get-mail.tk', 'get1mail.com', 'get2mail.fr', 'getairmail.cf', 'getairmail.com', 'getairmail.ga', 'getairmail.gq', 'getairmail.ml', 'getairmail.tk', 'gethotlink.com', 'getmails.eu', 'getonemail.com', 'getonemail.net', 'ghosttexter.de', 'giantmail.de', 'girlsundertheinfluence.com', 'gishpuppy.com', 'gmial.com', 'gmx.com', 'goemailgo.com', 'gomail.in', 'gorillaswithdirtyarmpits.com', 'gotmail.com', 'gotmail.net', 'gotmail.org', 'gotti.otherinbox.com', 'gowikibooks.com', 'gowikicampus.com', 'gowikicars.com', 'gowikifilms.com', 'gowikigames.com','gowikimusic.com', 'gowikinetwork.com', 'gowikitravel.com', 'gowikitv.com', 'grandmamail.com', 'grandmasmail.com', 'great-host.in', 'green-eggs-n-spam.com', 'greensloth.com', 'grr.la', 'gsrv.co.uk', 'guerillamail.biz', 'guerillamail.com', 'guerillamail.net', 'guerillamail.org', 'guerrillamail.biz', 'guerrillamail.com', 'guerrillamail.de', 'guerrillamail.info', 'guerrillamail.net', 'guerrillamail.org', 'guerrillamailblock.com', 'guoji.nospammail.net', 'gustr.com', 'h.mintemail.com', 'h8s.org', 'ha.com', 'hacccc.com', 'haha.com', 'hahaha.com', 'hahahaha.com', 'hahahahaha.com', 'haltospam.com', 'harakirimail.com', 'hartbot.de', 'hat-geld.de', 'hatespam.org', 'hatespam.ru', 'hellodream.mobi', 'herp.in', 'hidden-email.com', 'hidemail.de', 'hidemyass.com', 'hidzz.com', 'hmamail.com', 'hochsitze.com', 'holyspam.com', 'hooohush.ai', 'hopemail.biz', 'hot-mail.cf', 'hot-mail.ga', 'hot-mail.gq', 'hot-mail.ml', 'hot-mail.tk', 'hotpop.com', 'huajiachem.cn', 'hulapla.de', 'hushmail.com', 'i2pmail.org', 'ieatspam.eu', 'ieatspam.info','ieh-mail.de', 'ihateyoualot.info', 'iheartspam.org', 'ikbenspamvrij.nl', 'ilikespam.com', 'imails.info', 'imexdia.com', 'imgof.com', 'imstations.com', 'inbax.tk', 'inbox.si', 'inboxalias.com', 'inboxclean.com', 'inboxclean.org', 'inboxed.im', 'inboxed.pw', 'inboxproxy.com', 'incognitomail.com', 'incognitomail.net', 'incognitomail.org', 'ineedtourinate.com', 'ineedtourinate.info', 'infocom.zp.ua', 'inoutmail.de', 'inoutmail.eu', 'inoutmail.info', 'inoutmail.net', 'insorg-mail.info', 'instancemail.net', 'instant-mail.de', 'instantemailaddress.com', 'ip6.li', 'ipoo.org', 'irish2me.com', 'iroid.com', 'isotram.com', 'istola.com', 'iwantmyname.com', 'iwi.net', 'izolink.com', 'jetable.com', 'jetable.fr.nf', 'jetable.net', 'jetable.org', 'jnxjn.com', 'jobbikszimpatizans.hu', 'jourrapide.com', 'jsrsolutions.com', 'junk1e.com', 'junkmail.com', 'junkmail.ga', 'junkmail.gq', 'justforspam.net', 'k2-herbal-incenses.com', 'kasmail.com', 'kasovo.com', 'kaspop.com', 'kasumedia.com', 'keepmymail.com', 'killmail.com', 'killmail.net', 'kimsdisk.com', 'kingsq.ga', 'kir.ch.tc', 'klassmaster.com', 'klassmaster.net', 'klzlk.com', 'kook.ml', 'koszmail.pl', 'kulturbetrieb.info', 'kurzepost.de', 'kurzepost.net', 'l33r.eu', 'labetteraverouge.at', 'lackmail.net', 'lags.us', 'landmail.co', 'lastmail.co', 'lavabit.com', 'lawlita.com', 'lazpon.com', 'lazyinbox.com', 'lehora.com', 'letmeeatspam.com', 'letthemeatspam.com', 'lhsdv.com', 'licaso.com', 'lifebyfood.com', 'link2mail.net', 'linkitme.com', 'linuxmail.so', 'litedrop.com', 'loadby.us', 'login-email.cf', 'login-email.ga', 'login-email.ml', 'login-email.tk', 'lol.com', 'lol.ovpn.to', 'lolfreak.net', 'lookugly.com', 'lopl.co.cc', 'lortemail.dk', 'lovebitco.in', 'lovemeleaveme.com', 'lr7.us', 'lr78.com', 'lroid.com', 'lukop.dk', 'luv2.us', 'm21.cc', 'm4ilweb.info', 'maboard.com', 'mail-filter.com', 'mail-temporaire.fr', 'mail.by', 'mail.me', 'mail.mezimages.net', 'mail.ru', 'mail.zp.ua', 'mail114.net', 'mail1a.de', 'mail21.cc', 'mail2rss.org', 'mail333.com', 'mail4trash.com', 'mailbidon.com', 'mailbiz.biz', 'mailblocks.com', 'mailbucket.org', 'mailcat.biz', 'mailcatch.com', 'mailde.de', 'mailde.info', 'maildrop.cc', 'maildrop.cf', 'maildrop.ga', 'maildrop.gq', 'maildrop.ml', 'maildx.com', 'maileater.com', 'maileimer.de', 'mailexpire.com', 'mailfa.tk', 'mailforspam.com', 'mailfree.ga', 'mailfree.gq', 'mailfree.ml', 'mailfreeonline.com', 'mailfs.com', 'mailguard.me', 'mailimate.com', 'mailin8r.com', 'mailinater.com', 'mailinator.com', 'mailinator.gq', 'mailinator.net', 'mailinator.org', 'mailinator.us', 'mailinator2.com', 'mailincubator.com', 'mailismagic.com', 'mailjunk.cf', 'mailjunk.ga', 'mailjunk.gq', 'mailjunk.ml', 'mailjunk.tk', 'mailmate.com', 'mailme.gq', 'mailme.ir', 'mailme.lv', 'mailme24.com', 'mailmetrash.com', 'mailmoat.com', 'mailms.com', 'mailnator.com', 'mailnesia.com', 'mailnull.com', 'mailorg.org', 'mailpick.biz', 'mailproxsy.com', 'mailquack.com', 'mailrock.biz', 'mailsac.com', 'mailscrap.com', 'mailseal.de', 'mailshell.com', 'mailsiphon.com', 'mailslapping.com', 'mailslite.com','mailtemp.info', 'mailtome.de', 'mailtome.de', 'mailtothis.com', 'mailtrash.net', 'mailtv.net', 'mailtv.tv', 'mailwithyou.com', 'mailzilla.com', 'mailzilla.org', 'mailzilla.orgmbx.cc', 'makemetheking.com', 'malahov.de', 'manifestgenerator.com', 'manybrain.com', 'mbx.cc', 'mciek.com', 'mediatiny.com', 'mega.zik.dj', 'meinspamschutz.de', 'meltmail.com', 'messagebeamer.de', 'mezimages.net', 'michellekelly', 'michellekellyblog.com', 'michellestestarea.com', 'mierdamail.com', 'migmail.pl', 'migumail.com', 'ministry-of-silly-walks.de', 'mintemail.com', 'misterpinball.de', 'mjukglass.nu', 'mmmmail.com', 'moakt.com', 'mobi.web.id', 'mobileninja.co.uk', 'moburl.com', 'moncourrier.fr.nf', 'monemail.fr.nf', 'monmail.fr.nf', 'monumentmail.com', 'ms9.mailslite.com', 'msa.minsmail.com', 'msg.mailslite.com', 'mt2009.com', 'mt2014.com', 'mt2015.com', 'mx0.wwwnew.eu', 'my10minutemail.com', 'mycard.net.ua', 'mycleaninbox.net', 'myemailboxy.com', 'mymail-in.net', 'mymailoasis.com', 'mynetstore.de', 'mypacks.net', 'mypartyclip.de', 'myphantomemail.com', 'mysamp.de', 'myspaceinc.com', 'myspaceinc.net', 'myspaceinc.org', 'myspacepimpedup.com', 'myspamfilter.net', 'myspamless.com', 'mytemp.email', 'mytempemail.com', 'mytempmail.com', 'mythrashmail.net', 'mytrashmail.com', 'nabuma.com', 'nakyto.com', 'neomailbox.com', 'nepwk.com', 'nervmich.net', 'nervtmich.net', 'netmails.com', 'netmails.net', 'netzidiot.de', 'neverbox.com', 'nevermail.de', 'nice-4u.com', 'nincsmail.hu', 'nmail.cf', 'nnh.com', 'no-spam.pl', 'no-spam.us', 'no-spam.ws', 'no-spammers.com', 'noblepioneer.com', 'nobulk.com', 'noclickemail.com', 'noemail.com', 'nogmailspam.info', 'nomail.pw', 'nomail.xl.cx', 'nomail2me.com', 'nomorespamemails.com', 'nonespam.com', 'nonspam.eu', 'nonspammer.de', 'noref.in', 'nospam.33m.co', 'nospam.barbees.net', 'nospam.blairos.org', 'nospam.com', 'nospam.dnsalias.org', 'nospam.me', 'nospam.nowire.org', 'nospam.otteraa.com', 'nospam.wins.com.br', 'nospam.ze.tc', 'nospam12.de', 'nospam4.us', 'nospamfor.us', 'nospamfree.fr', 'nospamhere.com', 'nospamhere.eu', 'nospammail.net', 'nospammails.com', 'nospamplease.ws', 'nospamthanks.info', 'notathoughtgiven', 'notathoughtgiven.audio', 'notathoughtgiven.com', 'notathoughtgiven.mobi', 'notathoughtgiven.today', 'notmailinator.com', 'notsharingmy.info', 'nowhere.org', 'nowmymail.com', 'nurfuerspam.de', 'nus.edu.sg', 'nwldx.com', 'o2.co.uk', 'o2.pl', 'objectmail.com', 'obobbo.com', 'odaymail.com', 'odnorazovoe.ru', 'omail.pro', 'one-time.email', 'oneoffemail.com', 'oneoffmail.com', 'onewaymail.com', 'onlatedotcom.info', 'online.ms', 'oopi.org', 'opayq.com', 'opentrash.com', 'ordinaryamerican.net', 'otherinbox.com', 'ourklips.com', 'outlawspam.com', 'ovpn.to', 'owlpic.com', 'pancakemail.com', 'paplease.com', 'paq.com', 'pcusers.otherinbox.com', 'pepbot.com', 'petspotkennels.com', 'pfui.ru', 'phentermine-mortgages-texas-holdem.biz', 'pimpedupmyspace.com', 'pjjkp.com', 'plexolan.de', 'poczta.onet.pl', 'politikerclub.de', 'ponyda.com', 'poofy.org', 'pookmail.com', 'porn.com', 'powered.name', 'privacy.net', 'privatdemail.net', 'privy-mail.com', 'privy-mail.de', 'privymail.de', 'proxymail.eu', 'prtnx.com', 'prtz.eu', 'punkass.com', 'putthisInyourspamdatabase.com', 'putthisinyourspamdatabase.com', 'pwrby.com', 'pzclips.com', 'qq.com', 'quickinbox.com', 'quickmail.nl', 'rcpt.at', 're-gister.com', 'reallymymail.com', 'realtyalerts.ca', 'receiveee.com', 'recode.me', 'recursor.net', 'recyclemail.dk', 'regbypass.com', 'regbypass.comsafe-mail.net', 'rejectmail.com', 'reliable-mail.com', 'remail.cf', 'remail.ga', 'rhyta.com', 'riddle.com', 'rklips.com', 'rmqkr.net', 'rocketmail.com', 'rolaza.com', 'royal.net', 'rppkn.com', 'rtrtr.com', 'ruffrey.com', 's0ny.net', 'safe-mail.net', 'safersignup.com', 'safersignup.de', 'safetymail.info', 'safetypost.de', 'sandelf.de', 'saynotospams.com', 'scatmail.com', 'schafmail.de', 'schrott-email.de', 'secmail.pw', 'secretemail.de', 'secure-mail.biz', 'secure-mail.cc',  'selfdestructingmail.com', 'selfdestructingmail.org', 'sendspamhere.com', 'services391.com' ,'sharedmailbox.org', 'sharklasers.com', 'shieldedmail.com', 'shieldemail.com', 'shiftmail.com', 'shitmail.me', 'sibmail.com', 'sina.cn', 'sina.com', 'sinnlos-mail.de', 'siteposter.net', 'skeefmail.com', 'sky-ts.de', 'slapsfromlastnight.com', 'slaskpost.se', 'slave-auctions.net', 'slopsbox.com', 'slushmail.com', 'smashmail.de', 'smellfear.com', 'smellrear.com', 'smith.com', 'snakemail.com', 'sneakemail.com', 'sneakmail.de', 'sneakydave.com', 'snkmail.com', 'sociallycensored', 'sociallycensored.info', 'sociallycensored.rocks', 'sofimail.com', 'sofort-mail.de', 'softpls.asia', 'sogetthis.com', 'sohu.com', 'soisz.com', 'solvemail.info', 'soodomail.com', 'soodonims.com', 'spam-be-gone.com', 'spam-prohibition.de', 'spam.24.odessa.ua', 'spam.benkelberg.net', 'spam.com', 'spam.cpels.com', 'spam.de', 'spam.isix.net', 'spam.la', 'spam.lv', 'spam.no', 'spam.pableu.net', 'spam.scholdan.de', 'spam.seydisehirmyo.net', 'spam.slzm.de', 'spam.su', 'spam.trajano.net', 'spam.wolvesbane.net', 'spam.zeitproblem.de', 'spam2000.sent.com', 'spam2002.sent.com', 'spam4.me', 'spamaert.com', 'spamail.com', 'spamail.de', 'spamarrest.com', 'spamavert.com', 'spambob.com', 'spambob.net', 'spambob.org', 'spambog.com', 'spambog.de', 'spambog.net', 'spambog.ru', 'spambox.info', 'spambox.irishspringrealty.com', 'spambox.us', 'spambox.zone-salting.info', 'spamcannon.com', 'spamcannon.net', 'spamcatcher.org', 'spamcero.com', 'spamcon.org', 'spamcontrol.co.uk', 'spamcop.net', 'spamcorpastic.com', 'spamcorptastic.com', 'spamcowboy.com', 'spamcowboy.net', 'spamcowboy.org', 'spamday.com', 'spamdecoy.net', 'spamdrop.net', 'spamelka.com', 'spamertriebel.co.za', 'spamex.com', 'spamexautoparts.com', 'spamexperts.com', 'spamfence.net', 'spamfighter.cf', 'spamfighter.com', 'spamfighter.ga', 'spamfighter.gq', 'spamfighter.ml', 'spamfighter.tk', 'spamfilter.de', 'spamfinity.com', 'spamfree.eu', 'spamfree24.com', 'spamfree24.de', 'spamfree24.eu', 'spamfree24.info', 'spamfree24.net', 'spamfree24.org', 'spamgoes.in', 'spamgourmet.com', 'spamgourmet.net', 'spamgourmet.org', 'spamguard.wavell.net', 'spamherealots.com', 'spamherelots.com', 'spamhereplease.com', 'spamhole.com', 'spamify.com', 'spaminator.de', 'spamkill.info', 'spamkiste.de', 'spaml.com', 'spaml.de', 'spammail.com', 'spammers.com', 'spammotel.com', 'spamneggs.com', 'spamobox.com', 'spamoff.de', 'spamphree.com', 'spampit.co.uk', 'spamrobot.de', 'spamsalad.in', 'spamslicer.com', 'spamsoap.com', 'spamspot.com', 'spamstack.net', 'spamsy.com', 'spamtest.ru', 'spamthis.co.uk', 'spamthisplease.com', 'spamtracker.co.uk', 'spamtracker.org', 'spamtrail.com', 'spamtrap.spaceboy.com', 'spamtroll.net', 'spamund.cl', 'spamwatching.com', 'spamzor.beartiger.de', 'speed.1s.fr', 'spikio.com', 'spoofmail.de', 'squizzy.de', 'ssoia.com', 'startkeys.com', 'stewart1champ@gmail.com', 'stinkefinger.net', 'stop-my-spam.cf', 'stop-my-spam.com', 'stop-my-spam.ga', 'stop-my-spam.ml', 'stop-my-spam.tk', 'stopspamming.net', 'stuffmail.de', 'super-auswahl.de', 'supergreatmail.com', 'supermailer.jp', 'superplatyna.com', 'superrito.com', 'superstachel.de', 'supperito.com', 'suremail.info', 'svk.jp', 'sweetxxx.de', 'tafmail.com', 'tags.com', 'tagyourself.com', 'talkinator.com', 'tapchicuoihoi.com', 'teewars.org', 'teleworm.com', 'teleworm.us', 'temp-mail.org', 'temp-mail.ru', 'temp.emeraldwebmail.com', 'temp.headstrong.de', 'tempail.com', 'tempalias.com', 'tempe-mail.com', 'tempemail.biz', 'tempemail.co.za', 'tempemail.com', 'tempemail.net', 'tempimbox.com', 'tempinbox.co.uk', 'tempinbox.com', 'tempmail.eu', 'tempmail.it', 'tempmail2.com', 'tempmaildemo.com', 'tempmailer.com', 'tempmailer.de', 'tempomail.fr', 'temporarily.de', 'temporarioemail.com.br', 'temporaryemail.net', 'temporaryemail.us', 'temporaryforwarding.com', 'temporaryinbox.com', 'temporarymailaddress.com', 'tempsky.com', 'tempthe.net', 'tempymail.com', 'thanksnospam.info', 'thankyou2010.com', 'thc.st', 'thecloudindex.com', 'thelimestones.com', 'thisisnotmyrealemail.com', 'thismail.net', 'throam.com', 'throwawayemailaddress.com', 'throwawaymail.com', 'tilien.com', 'tits.com', 'tittbit.in', 'tizi.com', 'tmail.ws', 'tmailinator.com', 'toiea.com', 'tokem.co', 'tokenmail.de', 'tomrank.com', 'toomail.biz', 'topcoolemail.com', 'topfreeemail.com', 'topranklist.de', 'tormail.net', 'tormail.org', 'totallyfunny.net', 'tradermail.info', 'transgenderftm.info', 'transgendermtf.info', 'transgendernonop.info', 'trash-amil.com', 'trash-mail.at', 'trash-mail.cf', 'trash-mail.com', 'trash-mail.de', 'trash-mail.ga', 'trash-mail.gq', 'trash-mail.ml', 'trash-mail.tk', 'trash-me.com', 'trash2009.com', 'trash2010.com', 'trash2011.com', 'trashdevil.com', 'trashdevil.de', 'trashemail.de', 'trashmail.at', 'trashmail.com', 'trashmail.de', 'trashmail.me', 'trashmail.net', 'trashmail.org', 'trashmail.ws', 'trashmailer.com', 'trashymail.com', 'trashymail.net', 'trayna.com', 'trbvm.com', 'trialmail.de', 'trickmail.net', 'trillianpro.com', 'tryalert.com', 'tubeagain.com', 'turual.com', 'twinmail.de', 'twoweirdtricks.com', 'tyldd.com', 'ubismail.net', 'uggsrock.com', 'umail.net', 'unmail.ru', 'upliftnow.com', 'uplipht.com', 'uroid.com', 'us.af', 'username.e4ward.com', 'valemail.net', 'veggiespam.com', 'venompen.com', 'verdespampas.com.br', 'verticalscope.com', 'veryrealemail.com', 'veryrealmail.com', 'vidchart.com', 'viditag.com', 'viewcastmedia.com', 'viewcastmedia.net', 'viewcastmedia.org', 'vipmail.name', 'vipmail.pw', 'viralplays.com', 'vistomail.com', 'vomoto.com', 'vpn.st', 'vsimcard.com', 'vubby.com', 'walala.org', 'walkmail.net', 'wasteland.rfc822.org', 'watch-harry-potter.com', 'watchfull.net', 'webemail.me', 'webm4il.in', 'webm4il.info', 'webuser.in', 'wee.my', 'weg-werf-email.de', 'wegwerf-email-addressen.de', 'wegwerf-email-adressen.de', 'wegwerf-emails.de', 'wegwerfadresse.de', 'wegwerfemail.com', 'wegwerfemail.de', 'wegwerfemailadresse.com', 'wegwerfmail.de', 'wegwerfmail.info', 'wegwerfmail.net', 'wegwerfmail.org', 'wetrainbayarea.com', 'wetrainbayarea.org', 'wh4f.org', 'whatiaas.com', 'whatpaas.com', 'whatsaas.com', 'whopy.com', 'whtjddn.33mail.com', 'whyspam.me', 'wickmail.net', 'wilemail.com', 'willhackforfood.biz', 'willselfdestruct.com', 'winemaven.in', 'winemaven.info', 'wmail.cf', 'writeme.us', 'wronghead.com', 'wuzup.net', 'wuzupmail.net', 'www.e4ward.com', 'www.gishpuppy.com', 'www.mailinator.com', 'wwwnew.eu', 'x.ip6.li', 'xagloo.com', 'xapportal.com', 'xemaps.com', 'xenforo.com', 'xents.com', 'xmaily.com', 'xn--aboutmichellekely-d0c.com', 'xoxox.cc', 'xoxy.net', 'xxtreamcam.com', 'xyzfree.net', 'yahoo.com.ph', 'yahoo.com.vn', 'yandex.com', 'yanet.me', 'yapped.net', 'yeah.net', 'yep.it', 'yogamaven.com', 'yopmail.com', 'yopmail.fr', 'yopmail.gq', 'yopmail.net', 'you-spam.com', 'youmail.ga', 'youmailr.com', 'yourdomain.com', 'youspamyoudie.com', 'yoyoclips.com', 'ypmail.webarnak.fr.eu.org', 'yspam4.me', 'yuurok.com', 'z1p.biz', 'za.com', 'zehnminuten.de', 'zehnminutenmail.de', 'zerospam.ca', 'zetmail.com', 'zippymail.in', 'zippymail.info', 'zoaxe.com', 'zoemail.com', 'zoemail.net', 'zoemail.org', 'zomg.info', 'zomg.info0815.ru', 'zxcv.com', 'zxcvbnm.com', 'zzz.com'
		    );
	     	$messageAfterFilter =  str_replace($spamEmails, '',$email);
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
				$queryemail = $this->db->prepare("SELECT * FROM `users` WHERE `email` = :email");
				$queryemail->execute(array("email"=>self::sanitize($email)));
				$resultemail = $queryemail->fetch(PDO::FETCH_ASSOC);
				if(!$resultemail)
				{
                 if($pass == $repass)
                 {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					 if($result['ipaddress'] >= 1)
					 {
						 return "ipbanned";
					 }
					 else
					 {
						 $query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						 if($result['latestip'] >= 2)
						 {
							 return "iperror";
						 }
						 else
						 {
							 if (filter_var($email, FILTER_VALIDATE_EMAIL))
							  {
							  	if(empty($_POST['g-recaptcha-response'])){
							    return "empty-cap";
                                }
					            else
					            {
					            	if(strlen($messageAfterFilter) != strlen($email))
								    {
									    return 'spam';
								    }
								    else
								    {
							    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								/*$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

								$queryinsert = $this->db->prepare("INSERT INTO `users` (`username`,`password`,`email`,`sig`,`latestip`) VALUES (:username, :password, :email, :sig, :latestip)");
								$queryinsert->execute(array('username'=>self::sanitize($user),'password'=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),'email'=>self::sanitize($email),'sig:'=>self::sanitize($license),'latestip'=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/

								$this->insert_query("users",
				                array("username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP'],
								"verified"=>"0"));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("register_logs",
                                    array("username"=>self::sanitize($user),
                                    "email"=>self::sanitize($email),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser(),
                                    "city"=>$this->getGeoCity(),
			                        "isp"=>$this->getGeoISP(),
			                        "browser"=>$this->getTheBrowser(),
                                    "user_agent"=>$this->getUserAgent()));

								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
								 return "success";
							    }
							}
							    if(!($user -> captcha($_POST['g-recaptcha-response'], "6LeOL6wZAAAAAOSHPIP-6CVJ1zZGi2TXyGiOqs13"))){     
						        return "incorrect-cap";
                                }
					            else
						        {
                                $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								
								$this->insert_query("users",
				                array("username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP'],
								"verified"=>"0"));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("register_logs",
                                    array("username"=>self::sanitize($user),
                                    "email"=>self::sanitize($email),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser(),
                                    "city"=>$this->getGeoCity(),
			                        "isp"=>$this->getGeoISP(),
			                        "browser"=>$this->getTheBrowser(),
                                    "user_agent"=>$this->getUserAgent()));

								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							   	return "success";
                                }

								}
								  else
								{
									return "email_error";
								}
							}
						}
	                }
	                else
	                {
	                 	return "password_dm";
	                }
	            }
	            else 
	            {
	            	return "email_taken";
	            }
			}
			else
			{
                return "user_taken";
			}
		}
		public function register1($user,$pass,$repass,$email)
		{
			/*$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
                 if($pass == $repass)
                 {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					 if($result['ipaddress'] >= 1)
					 {
						 return "ipbanned";
					 }
					 else
					 {
						 $query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						 if($result['latestip'] >= 2)
						 {
							 return "iperror";
						 }
						 else
						 {
							 if (filter_var($email, FILTER_VALIDATE_EMAIL))
							  {
							  	if(empty($_POST['g-recaptcha-response'])){
							    return "empty-cap";
                                }
					            else
					            {
							    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
								"sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                                    
                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                       
								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							    return "success";
								}
							    if(!($user -> captcha($_POST['g-recaptcha-response'], "6LfexssZAAAAANsq3_hMunpU68NBQouJNUoxKmJe"))){     
						        return "incorrect-cap";
                                }
					            else
						        {
                                $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								$this->insert_query("users", array(
								"username"=>self::sanitize($user),
								"password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								"email"=>self::sanitize($email),
							    "sig"=>self::sanitize($license),
								"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                $this->insert_query("active_users",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));

                                 $this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                "username"=> $this->getUserName(self::sanitize($user)),
                                "latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));
                             
								$query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								$query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								$result = $query->fetch(PDO::FETCH_ASSOC);
								if($result){
								$this->update("referralid", array("currentAmount"=>$result['currentAmount'] + 1,"totalAmount"=>$result['totalAmount'] + 1), "refid", $this->sanitize($_POST['RefsIdKek']));
								}
							   return "success";
                                }

							}
							  else
							{
								return "email_error";
							}
						}
					}
                }
                else
                {
                 	return "password_dm";
                }
			}
			else
			{
                return "user_taken";
			}*/
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :license");
			$query->execute(array("license"=>self::sanitize($user)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result)
			{
                if($pass == $repass)
                {
					$query = $this->db->prepare("SELECT * FROM `bannedips` WHERE `ipaddress` = :ip");
					$query->execute(array("ip"=>$this->getUserIpAddress()));
					$result = $query->fetch(PDO::FETCH_ASSOC);
					if($result['ipaddress'] >= 1)
					{
						return "ipbanned";
					}
					else
					{
						$query = $this->db->prepare("SELECT * FROM `users` WHERE `latestip` = :latestip");
						$query->execute(array("latestip"=>$this->getUserIpAddress()));
						$result = $query->fetch(PDO::FETCH_ASSOC);
						if($result['latestip'] >= 2)
						{
							 return "iperror";
						}
						else
						{
							if (filter_var($email, FILTER_VALIDATE_EMAIL))
							{
							  	if(empty($_POST['g-recaptcha-response']))
							  	{
							        return "empty-cap";
                                }
					            else
					            {
					            	
							        $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								    $this->insert_query("users", 
								    array("username"=>self::sanitize($user),
								    "password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								    "email"=>self::sanitize($email),
								    "sig"=>self::sanitize($license),
								    "latestip"=>$this->getUserIpAddress(),
								    "credits"=>"50"));
                                    
                                    $this->insert_query("active_users",
                                    array("userid"=>$result['id'] = $this->getUserId(self::sanitize($user)),
                                    "admin"=>"0",
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "latestip"=>$this->getUserIpAddress()));

                                    $this->insert_query("login_logs",
                                    array("userid"=>$this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser()));

                                    return "success";

                                    //$this->setLoginCookie($this->getUserId(self::sanitize($user)).$this->getUserEmail(self::sanitize($user)), 2147483647);

                                    /*$this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/
                       
								    $query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								    $query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								    $result = $query->fetch(PDO::FETCH_ASSOC);
								    if($result)
								    {
								        $this->update("referralid", 
								        array("currentAmount"=>$result['currentAmount'] + 1,
								        "totalAmount"=>$result['totalAmount'] + 1),
								        "refid", $this->sanitize($_POST['RefsIdKek']));
								    }
								    //$loggedlogin = $this->LoginLog($this->getUserName(self::sanitize($user)));
							            return "success";
								    }
							        if(!($user->captcha($_POST['g-recaptcha-response'], "6LeOL6wZAAAAAOSHPIP-6CVJ1zZGi2TXyGiOqs13")))
							        {     
						                return "incorrect-cap";

                                }
					            else
						        {

                                    $license = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
								    $this->insert_query("users", 
								    array("username"=>self::sanitize($user),
								    "password"=>self::sanitize(password_hash($pass, PASSWORD_BCRYPT)),
								    "email"=>self::sanitize($email),
								    "sig"=>self::sanitize($license),
								    "latestip"=>$this->getUserIpAddress(),
								    "credits"=>"50"));

                                    $this->insert_query("active_users",
                                    array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "admin"=>"0",
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "latestip"=>$this->getUserIpAddress()));

                                    $this->insert_query("login_logs",
                                    array("userid"=>$this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),
                                    "ip"=>$this->getUserIpAddress(),
                                    "country"=>$this->getGeoCountry(),
                                    "browser"=>$this->getTheBrowser()));

                                    //$this->setLoginCookie($this->getUserId(self::sanitize($user)).$this->getUserEmail(self::sanitize($user)), 2147483647);

                                    /*$this->insert_query("black_list",array("userid" => $result['id'] = $this->getUserId(self::sanitize($user)),
                                    "username"=> $this->getUserName(self::sanitize($user)),"latestip"=>$_SERVER['HTTP_CF_CONNECTING_IP']));*/
                             
								    $query = $this->db->prepare("SELECT * FROM `referralid` WHERE `refid` = :ref");
								    $query->execute(array("ref"=>self::sanitize($_POST['RefsIdKek'])));
								    $result = $query->fetch(PDO::FETCH_ASSOC);
								    if($result)
								    {
								        $this->update("referralid", 
								        array("currentAmount"=>$result['currentAmount'] + 1,
								    	"totalAmount"=>$result['totalAmount'] + 1), 
								    	"refid", $this->sanitize($_POST['RefsIdKek']));
								    }
							            return "success";
                                }
							}
							else
							{
								return "email_error";
							}
						}
					}
                }
                else
                {
                 	return "password_dm";
                }
			}
			else
			{
                return "user_taken";
			}
		}
		/*public function LoginLog($user)
		{
			$userid = $this->getUserId($user);
			$this->insert_query("login_logs",
                array("userid"=> $this->getUserId(self::sanitize($user)),
                "username"=> $this->getUserName(self::sanitize($user)),
                "latestip"=>$this->getUserIpAddress(),
                "country"=>$this->getGeoCountry(),
                "browser"=>$this->getTheBrowser()));
		}*/
        public function resetpw($password, $repeat_password,$session)
		{
			if($password == $repeat_password)
			{
               $query = $this->db->prepare("SELECT * FROM `pw_reset` WHERE `session_id` = :license");
			   $query->execute(array("license"=>self::sanitize($session)));
			   $result = $query->fetch(PDO::FETCH_ASSOC);
			   if($result)
			   {
                  $this->update("users", array("password"=>self::sanitize(password_hash($password, PASSWORD_BCRYPT))), "id", $result['user_id']);
                  $query1 = $this->db->prepare("DELETE FROM `pw_reset` WHERE `session_id` = :id");
				  $query1->execute(array("id"=>self::sanitize($session)));
				  return "reset";
			   }
			   else
			   {
			   	   return "session";
			   }
			}
			else
			{
                return "pw";
			}
		} 
		public function forgotpw($email)
		{
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `email` = :license");
			$query->execute(array("license"=>self::sanitize($email)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($result)
			{
				$Session_id = $this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString()."-".$this->generateRandomString();
				$this->insert_query("pw_reset", array(
                "user_id"=>self::sanitize($result['id']),
                "session_id"=>self::sanitize($Session_id)));	
                return "sent";
			}
			else
			{
                 return "email";
			}
		}
		public function getGeoCity()
		{
			$ip = $this->getUserIpAddress();
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->city;
		}
		public function getGeoISP()
		{
			$ip = $this->getUserIpAddress();
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->isp;
		}
		public function getGeoCountry(){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
			$details = json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
			return $details->country; // -> "Mountain View"
		}
		public function login2($username,$password){
			$query = $this->db->prepare("SELECT * FROM `users` WHERE `username` = :username");
			$query->execute(array("username"=>self::sanitize($username)));
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if(!$result){
				$this->update("users", array("username"=>self::sanitize($username),"password"=>self::sanitize(password_hash($password, PASSWORD_BCRYPT))), "id", $_SESSION['id']);
				return "success";
			}else{
				return "exists";
			}
		}

		public  function getUserIpAddress() //HTTP_CF_CONNECTING_IP
		{
            $check_real_ip = '';
            if (getenv('HTTP_CLIENT_IP'))
            $check_real_ip = getenv('HTTP_CLIENT_IP');
            
            else if(getenv('HTTP_X_FORWARDED_FOR'))
            $check_real_ip = getenv('HTTP_X_FORWARDED_FOR');

            else if(getenv('HTTP_X_FORWARDED'))
            $check_real_ip = getenv('HTTP_X_FORWARDED');

            else if(getenv('HTTP_FORWARDED_FOR'))
            $check_real_ip = getenv('HTTP_FORWARDED_FOR');

            else if(getenv('HTTP_FORWARDED'))
            $check_real_ip = getenv('HTTP_FORWARDED');

            else if(getenv('REMOTE_ADDR'))
            $check_real_ip = getenv('REMOTE_ADDR');

            else
            $check_real_ip = 'UNKNOWN';
            return $check_real_ip;
        }

        public function getUserAgent() 
		{
			return $_SERVER['HTTP_USER_AGENT'];
		}
		public function getTheBrowser()
		{
			$UserPCAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
			
            if(preg_match('/Microsoft/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Microsoft Edge';
			}
			if(preg_match('/Firefox/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Firefox';
			}
			elseif(preg_match('/iPhone/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPhone';
			}
			elseif(preg_match('/iPad/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPad';
			}
			elseif(preg_match('/iPod/i',$UserPCAgent))
			{
				$getThereBrowser = 'Safari On iPod';
			}
			elseif(preg_match('/Mac/i',$UserPCAgent))
			{
				$getThereBrowser = 'Mac';
			}
			elseif(preg_match('/Chrome/i',$UserPCAgent)) 
			{
				$getThereBrowser = 'Google Chrome';
			}
			elseif(preg_match('/Opera/i',$UserPCAgent))
			{
				$getThereBrowser = 'Opera';
			}
			elseif(preg_match('/MSIE/i',$UserPCAgent))
			{
				$getThereBrowser = 'IE';
			}
			else
			{
				$getThereBrowser = 'Not Available!';
			}
			return $getThereBrowser;
		}

		public function UserVerify($verifyid)
		{
			$query = $this->db->prepare("SELECT * FROM `verify_links` WHERE `verify_id` = :verifyid");
		   	$query->execute(array("verifyid"=>self::sanitize($verifyid)));
		   	$result = $query->fetch(PDO::FETCH_ASSOC);
		   	if($result)
		   	{
		   		$query1 = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :username");
		   		$query1->execute(array(":username"=>self::sanitize($result['userid'])));
		   		$result1 = $query1->fetch(PDO::FETCH_ASSOC);
		   		if($result1['verified'] == 1)
		   		{
		   			return "already-verified";
		   		}
		   		else 
		   		{
		   			$this->update("users", array("verified"=>"1"), "id", $result['userid']);
		   			return "success";
		   		}
		   	}
		   	else
		   	{
		   		return "non-existing-id";
		   	}
		}

		public function SaveToolSettings($id,$value)
		{
			$this->update("toolSettings", array($id=>$value), "id", "1");
			return 1;
		}

		
}


