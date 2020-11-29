import { AxiosResponse } from 'axios';
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Posts, { IPost } from '../../components/PostList';
import api from '../../services/api';

function Home(): JSX.Element {
  const [posts, setPosts] = useState<IPost[] | []>([]);
  useEffect(() => {
    async function fetchPosts(): Promise<AxiosResponse<IPost[]>> {
      const loadedPosts: AxiosResponse<IPost[]> = await api.get('/posts');
      return loadedPosts;
    }

    fetchPosts().then(res => setPosts(res.data));
  });

  return (
    <>
      <Link to="/explore">Explore</Link>
      <div>Home</div>
      <Posts posts={posts} />
    </>
  );
}

export default Home;
