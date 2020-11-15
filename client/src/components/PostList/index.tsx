import { AxiosResponse } from 'axios';
import React, { useEffect } from 'react';

// import { Container } from './styles';

export interface IPost {
  image: string;
  description: string;
  userName: string;
}

export interface IPosts {
  loadPosts(): Promise<AxiosResponse<IPost[]>>;
}

const Posts: React.FC<IPosts> = ({ loadPosts }: IPosts) => {
  useEffect(() => {
    loadPosts();
  });
  return (
    <>
      <div>PostList</div>
      <ul title="postlist"></ul>
    </>
  );
};

export default Posts;
