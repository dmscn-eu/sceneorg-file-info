# Scene.org FileInfo Parser

Class to parse file information like for e.g. [fr-025-final2.zip](https://files.scene.org/view/demos/groups/farb-rausch/fr-025-final2.zip).


## Usage

    $service = new DmscnEu\SceneOrgFileInfo\Service($guzzleClient);
    $fileInfo = $service->getFileInfo($uri = 'http://de.scene.org/pub/parties/2014/atparty14/results.txt');
    
    echo $fileInfo->getId();

