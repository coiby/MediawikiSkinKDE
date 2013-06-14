<?php
/**
 * KDE 
 *
 *
 * @todo document
 * @file
 * @ingroup Skins
 * @author Yunhe Guo<guoyunhebrave@gmail.com>, Coiby Xu(Coiby.Xu@gmail.com)
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @TODO what's addModuleStyles for
 * @ingroup Skins
 */
class SkinKDE extends SkinTemplate {
	/** Using KDE. */
	var $skinname = 'KDE', $stylename = 'KDE',
		$template = 'KDETemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;
		parent::setupSkinUserCss( $out );

		// $out->addModuleStyles( 'skins.KDE' );
		$out->addStyle( 'kde/main.css', 'screen' );
		// Ugh. Can't do this properly because $wgHandheldStyle may be a URL
	
		if( $wgHandheldStyle ) {
			// Currently in testing... try 'chick/main.css'
			$out->addStyle( $wgHandheldStyle, 'handheld' );
		}

	}
}
/**
 * @todo document
 * @ingroup Skins
 */
class KDETemplate extends BaseTemplate {

	/**
	 * @var Skin
	 */
	var $skin;

	/**
	 * Template filter callback for KDE skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgVectorUseIconWatch, $wgStylePath ;

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		if ( $wgVectorUseIconWatch ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
			if ( isset( $nav['actions'][$mode] ) ) {
				$nav['views'][$mode] = $nav['actions'][$mode];
				$nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
				$nav['views'][$mode]['primary'] = true;
				unset( $nav['actions'][$mode] );
			}
		}

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];
		$this->skin = $this->data['skin'];

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?>
<script src="<?php echo $wgStylePath ?>/kde/jquery-2.0.0.min.js"></script>
<script>
    function toTop() {
      $('body,html').animate({scrollTop:0},500);
    }
    function openMenu() {
      m = document.getElementById("menu");
      if( m.style.height == '0px' || m.style.height == 0 ){
	m.style.height = 'auto';
      }
      else{
        m.style.height = '0px';
      }
    }
    function openPageMenu() {
      m = document.getElementById("page-menu");
      if( m.style.height == '0px' || m.style.height == 0 ){
	m.style.height = 'auto';
      }
      else{
        m.style.height = '0px';
      }
    }
    function openUserMenu() {
      m = document.getElementById("user-menu");
      if( m.style.height == '0px' || m.style.height == 0 ){
	m.style.height = 'auto';
      }
      else{
        m.style.height = '0px';
      }
    }
    </script>
<div id="content">
		<!-- start content -->
<?php $this->html('bodytext') ?>
		<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
		<!-- end content -->
		<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
		<div class="visualClear"></div>
	
</div>
<!-- printfooter -->
				<div class="siteinfo">
				<?php $this->html( 'printfooter' ); ?>
				</div>
				<!-- /printfooter -->
<!--float icon-->
    <div class="float-icon" style="left:60px;" onclick="openMenu();">
      <img src="<?php echo $wgStylePath ?>/kde/menu.png" title="打开菜单"></img>
    </div>
    <div class="float-icon" style="right:60px;" onclick="toTop();">
      <img src="<?php echo $wgStylePath ?>/kde/top.png" title="回到顶部"></img>
    </div>
<!-- /float icon -->
<!--menu-->
    <div id="menu" class="menu" style="left:60px;bottom:120px;">
      <div style="padding:10px;">
      <?php $this->renderPortals( $this->data['sidebar'] ); ?>
    </div>
    </div>
<!--/menu-->
<!--page menu-->
    <div id="page-menu" class="menu" style="bottom:60px;left:320px;">
      <div style="padding:10px;">
       <ul>
	<?php foreach ( $this->data['action_urls'] as $link ): ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php endforeach; ?>        
</ul>
	    </div>
	</div>
<!--/page menu-->
<!--user menu-->
    <div id="user-menu" class="menu" style="bottom:60px;right:10px;">
      <div style="padding:10px;">
<ul<?php $this->html( 'userlangattributes' ) ?>>
<?php			foreach( $this->getPersonalTools() as $key => $item ) { ?>
		<?php echo $this->makeListItem( $key, $item ); ?>

<?php			} ?>
	</ul>
      </div>
    </div>
<!--/user menu-->
<!--toolbar-->
    <div class="toolbar">
    <table width="100%">
    <tr>
    <td>
	<?php foreach ( $this->data['namespace_urls'] as $link ): ?>
			
			<!--check if contain talk-->
			<?php if (strpos($link['attributes'],'nstab') !== false) { ?>
			<td>
			<div class="icon">
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>"><img src="<?php echo $wgStylePath ?>/kde/page.png" title="<?php echo  htmlspecialchars( $link['text'] ) ?>"></a>
			</div>
			</td>
			<?php			} else if (strpos($link['attributes'],'talk') !== false){ ?>
			<td>
			<div class="icon">
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>"><img src="<?php echo $wgStylePath ?>/kde/talk.png" title="<?php echo  htmlspecialchars( $link['text'] ) ?>"></a>
			</div>
			</td>
			<?php } ?>
		<?php endforeach; ?>
		<?php foreach ( $this->data['view_urls'] as $link ): ?>
			<?php if (strpos($link['attributes'],'view') !== false) { ?>
			<td>
			<div class="icon">
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>"><img src="<?php echo $wgStylePath ?>/kde/read.png" title="<?php echo  htmlspecialchars( $link['text'] ) ?>"></a>
			</div>
			</td>
			<?php			} else if (strpos($link['attributes'],'edit') !== false){ ?>
			<td>
			<div class="icon">
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>"><img src="<?php echo $wgStylePath ?>/kde/edit.png" title="<?php echo  htmlspecialchars( $link['text'] ) ?>"></a>
			</div>
			</td>
			<?php			} else if (strpos($link['attributes'],'history') !== false){ ?>
			<td>
			<div class="icon">
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>"><img src="<?php echo $wgStylePath ?>/kde/history.png" title="<?php echo  htmlspecialchars( $link['text'] ) ?>"></a>
			</div>
			</td>
			<?php } ?>
		<?php endforeach; ?>
    <td>
      <div id="more" class="icon" onclick="openPageMenu();">
	<img src="<?php echo $wgStylePath ?>/kde/more.png" title="更多"></img>
      </div>
    </td>
    <td width="100%">
    </td>
    <td>
	<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
		<?php if ( $wgVectorUseSimpleSearch && $this->getSkin()->getUser()->getOption( 'vector-simplesearch' ) ): ?>
		<div id="simpleSearch">
			<?php if ( $this->data['rtl'] ): ?>
			<?php endif; ?>
			<?php echo $this->makeSearchInput( array( 'id' => 'searchInput', 'type' => 'text' ) ); ?>
			<?php if ( !$this->data['rtl'] ): ?>
			<?php endif; ?>
		<?php else: ?>
		<div>
			<?php echo $this->makeSearchInput( array( 'id' => 'searchInput' ) ); ?>
		<?php endif; ?>
			<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
		</div>
	</form>
    </td>
    <td>
      <div id="user" class="icon" onclick="openUserMenu();">
	<img src="<?php echo $wgStylePath ?>/kde/user.png"></img>
      </div>
    </td>
    </tr>
    </div>
<!--/toolbar-->
<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/

	 /* Render a series of portals
	 *
	 * @param $portals array
	 */
	protected function renderPortals( $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals['SEARCH'] ) ) {
			$portals['SEARCH'] = true;
		}
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false )
				continue;

			echo "\n<!-- {$name} -->\n";
			switch( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
				break;
			}
			echo "\n<!-- /{$name} -->\n";
		}
	}
	/**
	 * @param $name string
	 * @param $content array
	 * @param $msg null|string
	 * @param $hook null|string|array
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		?>
	<p<?php $this->html( 'userlangattributes' ) ?>><?php $msgObj = wfMessage( $msg ); echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ); ?></p>
<?php
		if ( is_array( $content ) ): ?>
		<ul>
<?php
			foreach( $content as $key => $val ): ?>
			<?php echo $this->makeListItem( $key, $val ); ?>

<?php
			endforeach;
			if ( $hook !== null ) {
				wfRunHooks( $hook, array( &$this, true ) );
			}
			?>
		</ul>
<?php
		else: ?>
		<?php echo $content; /* Allow raw HTML block to be defined by extensions */ ?>
<?php
		endif; ?>
<?php
	}


} // end of class
?>

