<!doctype html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>

		<title>Progressive Web App Template</title>
		<meta name='description' content='PWA Template'>
		<meta name='author' content='Mark Hewitt www.mh1.co'>
		<meta name='robot' content='noindex, nofollow' />
		<meta name='theme-color' content='#2e3135'>

		<!-- Add to home screen for Safari on iOS -->
		<meta name='apple-mobile-web-app-capable' content='yes'>
		<meta name='apple-mobile-web-app-status-bar-style' content='black'>
		<meta name='apple-mobile-web-app-title' content='PWA Template'>
		<link rel='apple-touch-icon' href='/assets/images/launcher-icon-3x.png'>
		<meta name='msapplication-TileImage' content='/assets/images/launcher-icon-3x.png'>
		<meta name='msapplication-TileColor' content='#2e3135'>

		<link rel='manifest' href='/manifest.json'>

		<link rel='stylesheet' type='text/css' href='/assets/css/style.css'>
		
		<style>
		.add-button {
		  position: absolute;
		  top: 1px;
		  left: 1px;
		}
		</style>
	</head>
	<body>
		<div class='logo'>
			<div class='beginning'>SMS</div>
			<div class='loader'>
				<div class='blue'>
					<div class='red'></div>
				</div>
			</div>
			<div class='ending'>group</div>
		</div><br>

		<?=time()?>
		
        <button type="button" onclick="registerOneTimeSync()">One Time Sync</button>
		
		<button class="add-button">Add to home screen</button>

		<div class='offline-banner'>You are currently offline. While you can view your data, you cannot edit it. Please reconnect to a network in order to proceed.</div>
		<script>
		
const images = ['fox1','fox2','fox3','fox4'];
const imgElem = document.querySelector('img');

function randomValueFromArray(array) {
  let randomNo =  Math.floor(Math.random() * array.length);
  return array[randomNo];
}

setInterval(function() {
  let randomChoice = randomValueFromArray(images);
  imgElem.src = 'images/' + randomChoice + '.jpg';
}, 2000)

// Register service worker to control making site work offline

if('serviceWorker' in navigator) {
  navigator.serviceWorker
           .register('/pwa-examples/a2hs/sw.js')
           .then(function() { console.log('Service Worker Registered'); });
}

// Code to handle install prompt on desktop

let deferredPrompt;
const addBtn = document.querySelector('.add-button');
addBtn.style.display = 'none';

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent Chrome 67 and earlier from automatically showing the prompt
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Update UI to notify the user they can add to home screen
  addBtn.style.display = 'block';

  addBtn.addEventListener('click', (e) => {
    // hide our user interface that shows our A2HS button
    addBtn.style.display = 'none';
    // Show the prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the A2HS prompt');
        } else {
          console.log('User dismissed the A2HS prompt');
        }
        deferredPrompt = null;
      });
  });
});		
			/* SERVICE WORKER - REQUIRED */
				if ('serviceWorker' in navigator)
				{
					navigator.serviceWorker
					.register('./sw.js')
					.then(function(reg) {
						console.log("ServiceWorker registered ◕‿◕", reg);
					})
					.catch(function(error) {
						console.log("Failed to register ServiceWorker ಠ_ಠ", error);
					});
				}

function registerOneTimeSync() {
    if (navigator.serviceWorker.controller) {
        navigator.serviceWorker.ready.then(function(reg) {
            if (reg.sync) {
                reg.sync.register({
                        tag: 'oneTimeSync'
                    })
                    .then(function(event) {
                        console.log('Sync registration successful', event);
                    })
                    .catch(function(error) {
                        console.log('Sync registration failed', error);
                    });
            } else {
                console.log("Onw time Sync not supported");
            }
        });
    } else {
        console.log("No active ServiceWorker");
    }
}

			/* OFFLINE BANNER */
				function updateOnlineStatus()
				{
					var d = document.body;
					d.className = d.className.replace(/\ offline\b/,'');

					if (!navigator.onLine)
					{
						d.className += " offline";
					}
				}

				updateOnlineStatus();
				window.addEventListener
				(
					'load',
					function()
					{

						window.addEventListener('online',  updateOnlineStatus);
						window.addEventListener('offline', updateOnlineStatus);
					}
				);

			/* CHANGE PAGE TITLE BASED ON PAGE VISIBILITY */
				function handleVisibilityChange()
				{
					if (document.visibilityState == "hidden")
					{
						document.title = "Hey! Come back!";
					}
					else
					{
						document.title = original_title;
					}
				}
				var original_title = document.title;
				document.addEventListener('visibilitychange', handleVisibilityChange, false);

			/* NOTIFICATIONS */
				window.addEventListener('load', function ()
				{
					// At first, let's check if we have permission for notification
					// If not, let's ask for it
					if (window.Notification && Notification.permission !== "granted")
					{
						Notification.requestPermission(function (status)
						{
							if (Notification.permission !== status)
							{
								Notification.permission = status;
							}
						});
					}
				});
				function notifyMe(alert_title, alert_body)
				{
					var options =
					{
						body: alert_body,
						icon: 'assets/images/launcher-icon-4x.png',
					}

					// Let's check if the browser supports notifications
					if (!("Notification" in window))
					{
						alert("This browser does not support system notifications");
						return false;
					}

					// Let's check whether notification permissions have already been granted
					else if (Notification.permission === "granted")
					{
						// If it's okay let's create a notification
						var notification = new Notification(alert_title,options);
						return true;
					}

					// Otherwise, we need to ask the user for permission
					else if (Notification.permission !== 'denied')
					{
						Notification.requestPermission(function (permission)
						{
							// If the user accepts, let's create a notification
							if (permission === "granted")
							{
								var notification = new Notification(alert_title,options);
								return true;
							}
						});
					}

					// Finally, if the user has denied notifications and you 
					// want to be respectful there is no need to bother them any more.
					console.log("Notifications denied");
					return false;
				}
				//Usage:
				//notifyMe("Title goes here", "Body text goes here");
		</script>
	</body>
</html>