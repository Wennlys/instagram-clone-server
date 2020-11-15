import { AxiosResponse } from 'axios';
import React from 'react';
import { Link } from 'react-router-dom';
import Posts, { IPost } from '../../components/PostList';
import api from '../../services/api';

function Home(): JSX.Element {
  async function loadPosts(): Promise<AxiosResponse<IPost[]>> {
    return await api.get('/posts');
  }

  return (
    <>
      <Link to="/explore">Explore</Link>
      <div>Home</div>
      <Posts loadPosts={loadPosts} />
    </>
  );
}

export default Home;
