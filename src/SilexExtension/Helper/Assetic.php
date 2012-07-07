<?php

namespace SilexExtension\Helper;

use Symfony\Component\Finder\Finder;

use Assetic\Factory\LazyAssetManager,
    Assetic\AssetWriter,
    Assetic\AssetManager,
    Assetic\Extension\Twig\TwigResource;

class Assetic
{
    /**
     * @var AssetManager
     */
    protected $am;
    /**
     * @var AssetWriter
     */
    protected $writer;
    /**
     * @var LazyAssetManager
     */
    protected $lam;
    /** 
     * @var \Twig_Environment
     */
    protected $twig;
    
    /** 
     * @var \Twig_Loader_Filesystem
     */
    protected $loaders;
   
    /**
     * Ctor
     * 
     * @param AssetManager     $am
     * @param LazyAssetManager $lam
     * @param AssetWriter      $writer
     */
    public function __construct(AssetManager $am, LazyAssetManager $lam, AssetWriter $writer)
    {
        $this->am     = $am;
        $this->lam    = $lam;
        $this->writer = $writer;
    }
    
    /**
     * @param \Twig_Environment       $twig
     * @param \Twig_Loader_Filesystem $loader
     */
    public function setTwig(\Twig_Environment $twig, \Twig_Loader_Filesystem $loader) 
    {
        $this->twig   = $twig;
        $this->loader = $loader;
    }
    
    /**
     * Locates twig templates and adds their defined assets to the lazy asset manager
     */
    public function addTwigAssets()
    {
        if (!$this->twig instanceof \Twig_Environment) {
            throw new \LogicException('Twig environment not set'); 
        }
        
        $finder   = new Finder();
        $iterator = $finder->files()->name('*.twig')->in($this->loader->getPaths());
        
        foreach ($iterator as $file) {
            $resource = new TwigResource($this->loader, $file->getRelativePathname());
            $this->lam->addResource($resource, 'twig');
        }
    }
    
    /**
     * Dumps all the assets 
     */
    public function dumpAssets() 
    {
        $this->dumpManagerAssets($this->am);
        $this->dumpManagerAssets($this->lam);
    }
    
    /**
     * Dumps the assets of given manager
     * 
     * Doesn't use AssetWriter::writeManagerAssets since we also want to dump non-combined assets 
     * (for example, when using twig extension in debug mode).
     * 
     * @param AssetManager $am
     * @param AssetWriter  $writer
     */
    protected function dumpManagerAssets(AssetManager $am)
    {
        foreach ($am->getNames() as $name) {
            $asset   = $am->get($name);
            
            $formula = $am->getFormula($name);
            
            $this->writer->writeAsset($asset);
            
            if (!isset($formula[2])) {
                continue;
            }
            
            $debug   = isset($formula[2]['debug'])   ? $formula[2]['debug']   : $am->isDebug();
            $combine = isset($formula[2]['combine']) ? $formula[2]['combine'] : null;
            
            if (null !== $combine ? !$combine : $debug) {
                foreach ($asset as $leaf) {
                    $this->writer->writeAsset($leaf);
                } 
            }
        }
    }
}
