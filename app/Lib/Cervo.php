<?php
App::uses('HtmlHelper', 'View/Helper');

class Cervo {

    public static function role($User) {
        
        if ($ItemCallName == 'Song') {
            return __('Single');
        }elseif ($ItemCallName == 'Album') {
            return __('Album');
        }elseif ($ItemCallName == 'Ringtone') {
            return __('Sonnerie');
        }elseif ($ItemCallName == 'Video') {
            return __('Vidéo');
        }elseif ($ItemCallName == 'Ebook') {
            return __('E-Book');
        }
        return $ItemCallName;    
    }


    public static function ItemCallName($ItemCallName) {
        if ($ItemCallName == 'Song') {
            return __('Single');
        }elseif ($ItemCallName == 'Album') {
            return __('Album');
        }elseif ($ItemCallName == 'Ringtone') {
            return __('Sonnerie');
        }elseif ($ItemCallName == 'Video') {
            return __('Vidéo');
        }elseif ($ItemCallName == 'Ebook') {
            return __('E-Book');
        }
        return $ItemCallName;    
    }

    public static function getInfo($Song, $options = array()) {

        $HtmlHelper = new HtmlHelper(new View());

        if (!empty($Song['Ringtone'])) {
            $modelAlias = 'Ringtone';
        }else{
            $modelAlias = 'Song';
        }


        $Song = Hash::merge($options, $Song);

        if (!empty($Song[$modelAlias])) {
            $Song = Hash::merge($Song[$modelAlias], $Song);                    
        }


        $SongName = '';
        if (!empty($Song['tracknumber'])) $SongName .= $Song['tracknumber'].'. ';
        if ($Song['name']) $SongName .= $Song['name'];

        $return['name'] = $Song['name'];
        $return['nameAlbum'] = $SongName;


        if (in_array($modelAlias, array('Ringtone'))) {
            $return['singers'] = $Song['author'];
            $return['singerslink'] = $Song['author'];
        }else{
            
            $needModels = array('Artist', 'Featuring', 'Album');
            foreach ($needModels as $key => $Model) {
                if (!array_key_exists($Model, $Song)) {
                    trigger_error("Vous n'avez associer '".$Model."' dans le model '".$modelAlias."'");
                    $Song[$Model] = array();
                }
            }


            $listArtists = $listFeaturings = $listArtistsLink = $listFeaturings = $Singers = $SingersLink = array();

            $listArtists = Hash::combine($Song['Artist'], '{n}.id', '{n}.name');
            if (!empty($Song['Album']['Artist']) AND !$listArtists) {
                $listArtists[$Song['Album']['Artist']['id']] = $Song['Album']['Artist']['name'];
            }

            $listFeaturings = Hash::combine($Song['Featuring'], '{n}.id', '{n}.name');


            foreach ($listArtists as $idArtist => $nameArtist) {
                $listArtistsLink[$idArtist] = $HtmlHelper->tag('a', $nameArtist, array('href' => Router::url(array('plugin' => false, 'controller' => 'show', 'action' => 'artist', 'name' => mb_strtolower(Inflector::slug($nameArtist, '-')), 'id' => $idArtist))));
            }


            foreach ($Song['Featuring'] as $idArtist => $Featuring) {
                if(!empty($Featuring['visible'])){
                    $listFeaturingsLink[$Featuring['id']] = $HtmlHelper->tag('a', $Featuring['name'], array('href' => Router::url(array('plugin' => false, 'controller' => 'show', 'action' => 'artist', 'name' => mb_strtolower(Inflector::slug($Featuring['name'], '-')), 'id' => $Featuring['id']))));
                }else{
                    $listFeaturingsLink[$Featuring['id']] = $Featuring['name'];
                }

            }

            
            if ($listArtists) {
                $Singers[] = CakeText::toList($listArtists, '&');
                $SingersLink[] = CakeText::toList($listArtistsLink, '&');
            }
            if ($listFeaturings) {
                $Singers[] = CakeText::toList($listFeaturings, '&');
                $SingersLink[] = CakeText::toList($listFeaturingsLink, '&');
            }
            $SingerNames = CakeText::toList($Singers, 'Feat.');
            $SingerNamesLink = CakeText::toList($SingersLink, 'Feat.');
            $SingerNamesFullLink = str_replace('href="', 'href="'.Configure::read('App.fullBaseUrl'), $SingerNamesLink);
            
            $return['singers'] = $SingerNames;
            $return['singerslink'] = $SingerNamesLink;
            $return['singersfullink'] = $SingerNamesFullLink;
        }

        $return['duration'] = CakeTime::format($Song['duration'], '%M:%S');

        if (empty($Song['birthday'])) {
            $Song['birthday'] = $Song['release'];
        }

        $return['year'] = CakeTime::format($Song['birthday'], '%Y');

        $needFields = array('cover', 'mp3', 'bell', 'birthday');
        foreach ($needFields as $key => $field) {
            if (array_key_exists($field, $Song)) {
                $return[$field] = $Song[$field];
            }
        }

        return $return;
    }

    public static function AccountType($AccountInfos) {

        if (!empty($AccountInfos['Info'])) {
            //$AccountInfos = $AccountInfos['Info'];
        }

        foreach ($AccountInfos as $key => $Info) {

            if (!empty($Info['ref']) AND $Info['ref'] == 'Productor') {
                if ($Info['crew']) {
                    return array('Producteur / Label', 'Label');
                }else{
                    return array('Producteur / Label', 'Producteur');
                }
            }elseif (!empty($Info['ref']) AND $Info['ref'] == 'Artist') {
                if ($Info['crew']) {
                    return array('Artiste / Groupe / Collectif', 'Groupe');
                }else{
                    return array('Artiste / Groupe / Collectif', 'Artiste');
                }
            }elseif (!empty($Info['ref']) AND $Info['ref'] == 'Other') {
                return array('Editeur / Evénementiel', 'Autre');
            }
        }

        return array('Compte', 'Compte');

    }


    public static function Thumb($Song) {

        if (!empty($Song['Song'])) {
            $Song = Hash::merge($Song['Song'], $Song);
        }

        $coverFields = array(
            @$Song['Album']['cover'],
            @$Song['cover'],
            @$Song['Artist']['cover'],
            @$Song['Album']['Artist']['cover'],
        );

        foreach ($coverFields as $cover) {
            if (!$cover) {
                continue;
            }

            if ($cover AND is_file(WWW_ROOT.$cover)) {
                $SongThumb = $cover;
                break;
            }
        }

        if (empty($SongThumb)) {

            if (!empty($Song['Artist'])) {

                foreach ($Song['Artist'] as $key => $Artist) {

                    if (!empty($Artist['cover']) AND is_file(WWW_ROOT.$Artist['cover'])) {
                        $SongThumb = $Artist['cover'];
                        break;
                    }
                }

            }
        }

        if (empty($SongThumb)) {
            if (!empty($Song['Author'])) {
                $SongThumb = $Song['Author']['cover'];
            }else{
                $SongThumb = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            }            
        }

        return $SongThumb;
    }





    public static function ThumbArtist($Artist) {

        if (!empty($Artist['Artist'])) {
            $Artist = Hash::merge($Artist['Artist'], $Artist);
        }

        $coverFields = array(
            @$Artist['cover'],
            @$Artist['Album']['cover'],
            @$Artist['Artist']['cover'],
            @$Artist['Album']['Artist']['cover'],
        );

        foreach ($coverFields as $cover) {
            if (!$cover) {
                continue;
            }

            if ($cover AND is_file(WWW_ROOT.$cover)) {
                $ArtistThumb = $cover;
                break;
            }
        }

        if (empty($ArtistThumb)) {

            if (!empty($Artist['Song'])) {

                foreach ($Artist['Song'] as $key => $Song) {

                    if (!empty($Song['cover']) AND is_file(WWW_ROOT.$Song['cover'])) {
                        $ArtistThumb = $Song['cover'];
                        break;
                    }
                }

            }
        }

        if (empty($ArtistThumb)) {
            if (!empty($Artist['Author'])) {
                $ArtistThumb = $Artist['Author']['cover'];
            }else{
                $ArtistThumb = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            }            
        }

        return $ArtistThumb;
    }



    public static function toplayer($options) {

        extract($options);
        $return = array();

        if (empty($Songs) AND !empty($Song)) {
            $Songs = array($Song);
        }elseif (!empty($Ringtone)) {
            $Songs = array($Ringtone['Ringtone']);
        }

        if (empty($Songs)) {
            $Songs = array();
        }

        foreach ($Songs as $key => $Song) {

            if (!empty($Song['Song'])) {
                $Song = Hash::merge($Song['Song'], $Song);
            }

            if (file_exists(WWW_ROOT.$Song['bell'])) {

                $SongThumb = MD::Thumb($Song); 
                if (!empty($SongThumb)) {
                    $SongThumb = Router::url(Op::resizedURL($SongThumb, Configure::read('thumbOptions')));
                }



                $artist = '';
                $getInfo = MD::getInfo($Song);
                if (!empty($Song['Artist'][0])) {
                    $artists = Hash::extract($Song['Artist'], '{n}.name');
                    $artist = CakeText::toList($artists, '&');
                    $PlayerArtist = $Song['Artist'][0];
                }elseif (!empty($Song['Album']['Artist'])) {
                    $PlayerArtist = $Song['Album']['Artist'];   
                    $artist = $PlayerArtist['name'];
                }
                $artist = $getInfo['singers'];


                if (!empty($PlayerArtist)) {
                    $linkArtist = array('controller' => 'show', 'action' => 'artist', 'name' => mb_strtolower(Inflector::slug($PlayerArtist['name'], '-')), 'id' => $PlayerArtist['id']);
                }elseif (!empty($Song['author'])){
                    $linkArtist = $Song['author'];
                }else{
                    $linkArtist = '#';
                }


                $return [] = array(
                    'title' => $Song['name'],
                    'artist' => $artist.'{'.Router::url($linkArtist).'}',
                    'mp3' => Router::url(Ffmpeg::compressMp3($Song['bell'])),
                    'poster' => $SongThumb,
                );

            }

        }

        return $return;


    }
}