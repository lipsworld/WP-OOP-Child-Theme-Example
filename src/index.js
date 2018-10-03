import dva from 'dva';
import createLoading from 'dva-loading';
import './index.css';

// 1. Initialize
const app = dva();

app.use(createLoading({
    only: ['locations/search', 'locations/single', 'locations/fetch', ]
    // except: []
}));

// 2. Plugins
// app.use({});

// 3. Model
app.model(require('./models/locations').default);

// 4. Router
app.router(require('./router').default);

// 5. Start
app.start('#root');
