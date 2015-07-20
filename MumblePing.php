<?php
/*
 * A simple function to execute UDP Ping on Mumble servers.
 *
 * Author: xPaw
 * Website: http://xpaw.me
 * GitHub: https://github.com/xPaw/MumblePing
 * License: Unlicense, public domain
 */

namespace xPaw\Mumble;

/**
 * Sends a UDP ping to a Mumble server and returns info back.
 *
 * @param string $Address IP address of the server.
 * @param int    $Port    Port of the server.
 * @param int    $Timeout Seconds before socket times out.
 *
 * @return array|false Array with info on success, false on failure.
 */
function MumblePing( $Address, $Port = 64738, $Timeout = 3 )
{
	$Socket = @fsockopen( 'udp://' . $Address, $Port, $ErrNo, $ErrStr, $Timeout );
	
	if( $ErrNo || $Socket === false )
	{
		return false;
	}
	
	stream_set_timeout( $Socket, $Timeout );
	stream_set_blocking( $Socket, true );
	
	// 4 bytes = request type
	// 8 bytes = request ident
	$Command = "\x00\x00\x00\x00\x00\x78\x50\x61\x77\x2e\x6d\x65";
	
	fwrite( $Socket, $Command, 12 );
	
	// 4 bytes = version (e.g., \x0\x1\x2\x3 for 1.2.3.)
	// 8 bytes = request ident
	// 4 bytes = current users
	// 4 bytes = max users
	// 4 bytes = allowed bandwidth (in bytes)
	$Data = fread( $Socket, 24 );
	
	if( $Data === false )
	{
		return false;
	}
	
	$Info =
	[
		'Version'   => '',
		'Users'     => unpack( 'N', substr( $Data, 12, 4 ) )[ 1 ],
		'MaxUsers'  => unpack( 'N', substr( $Data, 16, 4 ) )[ 1 ],
		'Bandwidth' => unpack( 'N', substr( $Data, 20, 4 ) )[ 1 ],
	];
	
	for( $i = 0; $i < 4; $i++ )
	{
		if( $Data[ $i ] !== "\x00" )
		{
			$Info[ 'Version' ] .= ord( $Data[ $i ] );
			
			if( $Data[ $i + 1 ] !== "\x00" )
			{
				$Info[ 'Version' ] .= '.';
			}
		}
	}
	
	return $Info;
}
