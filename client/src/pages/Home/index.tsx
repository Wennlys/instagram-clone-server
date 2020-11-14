import React from 'react';
import { Link } from 'react-router-dom';

function Home(): JSX.Element {
  return (
    <>
      <Link to="/explore">Explore</Link>
      <div>Home</div>
    </>
  );
}

export default Home;
