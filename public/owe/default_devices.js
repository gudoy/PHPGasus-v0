/**
 * This file contains the default devices.
 *
 * The markup is in a transition state.
 *
 */


Control.devicesDefault = [
  {
    title: 'VGA Mobile',
    media: 'screen',
    screen: [480, 640],
    storage: 20480, // 20KB storage
    rotatable: true,
    chrome: [53,0,65,0],
    dock: [64, 64],
    useragent: null,
    plugins: ['Network Security', 'XHR Throttler']
  },
  {
    title: 'QVGA Mobile',
    media: 'screen',
    screen: [240, 320],
    storage: 20480, // 20KB storage
    rotatable: true,
    chrome: [26,0,33,0],
    dock: [64, 64],
    useragent: null,
    plugins: ['Network Security', 'XHR Throttler']
  },
  {
    title: 'WVGA Mobile',
    media: 'screen',
    screen: [800, 480],
    storage: 20480, // 20KB storage
    rotatable: true,
    chrome: [53,0,65,0],
    dock: [64, 64],
    useragent: null,
    plugins: ['Network Security', 'XHR Throttler']
  },
  {
    title: 'Desktop',
    media: 'screen',
    screen: [800, 600],
    storage: 2097152, // 2MB storage
    rotatable: true,
    chrome: null,
    dock: null,
    useragent: null,
    plugins: ['Network Security']
  },
  {
    title: 'TV',
    media: 'tv',
    screen: [800, 480],
    storage: 204800, // 200KB storage
    rotatable: false,
    chrome: null,
    dock: null,
    useragent: null,
    plugins: ['Network Security']
  }
];
