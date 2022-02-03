# The Object Manager

Basic usage:
<pre><code>use Merophp\ObjectManager\ObjectManager;
use MyVendor\MyBundle\MyHouse;

$mySampleInstance = ObjectManager::get(
    MyHouse:class,
    'constructor arg 1',
    'constructor arg 2'
);
</code></pre>

Using dependency injection:

<pre><code>namespace MyVendor\MyBundle;
use Merophp\ObjectManager\ObjectManager;
use MyVendor\MyBundle\MyRoof;

class MyHouse{

    /**
     * @var MyRoof
     */
    protected $myRoofInstance;

    public function injectMyRoof(MyRoof $myRoofInstance)
    {
        $this->myRoofInstance = $myRoofInstance;
    }
}
</code></pre>

By instantiating via the Object Manager, also an instance of <i>MyRoof</i>
will be instantiated and injected automatically to the <i>MyHouse</i> instance.
