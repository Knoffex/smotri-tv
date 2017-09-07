var player;
//var channels = [];
var hlsURL1 = 'http://tv1';
var hlsURL2 = '.fibernet.uz/hls/';
var hlsExt = 'ch.m3u8';

$(document).ready(function() {
		/*
        $.getJSON('/ajax/channels.json', function (json) {
                $.each(json.channels, function(index, item) {
                        // TODO - add parsing
                        //channels[index] = item;
                });
        });
        */

        player = new Clappr.Player(
                { 
                        source: hlsURL1 + '1' + hlsURL2 + '53' + hlsExt,
                        parentId: '.player',
                        width: $('.layer').width(),
                        height: $('.player').height()
                }
        );
        
        $.each(channels, function(index, item) {
                createChannelList(index, item);
        });
});

$(window).resize(function() {
		if(player) {
			player.resize(
					{
							width: $('.player').width(),
							height: $('.player').height()
					}
			);
        }
});

function resizePlayer() {
  if(player) {
    player.resize(
      {
        width: $('.player').width(),
        height: $('.player').height()
      }
    );
        }
};

function createChannelList(index, item)
{
        $('.channels ul').append(
                $('<li>').attr({ id: 'CH' + item.id }).append(
                        $('<a>').append(item.name)
                )
        );
        
        $('#CH' + item.id).click(function() {
                player.configure( {source: hlsURL1 + hlsURL2 + item.id + hlsExt, autoPlay: true} );
                $('.channels ul li').css("border-left", "none");
                $('#CH' + item.id).css("border-left", "5px solid #00b3fa");
        });
}
/*show-hide playlist*/
function showOrHide(box2, channels, player) {
				box2 = document.getElementById(box2);
				channels = document.getElementsByClassName("channels")[0];
				player = document.getElementsByClassName("player")[0];
				if (box2.checked) channels.style.display = "block";
				if (box2.checked) player.style.width = "75vw";
				else (channels.style.display = "none") && (player.style.width = "100%");
				resizePlayer();
				}

/*change playlist-position*/
function slideToggle(box1, channels, player) {
				box1 = document.getElementById(box1);
				channels = document.getElementsByClassName("channels")[0];
				player = document.getElementsByClassName("player")[0];
				if (box1.checked) channels.style.float = "left";
				if (box1.checked) player.style.float = "right";
				else (channels.style.float = "right") && (player.style.float="left");}
