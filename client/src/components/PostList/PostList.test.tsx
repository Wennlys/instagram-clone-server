import { screen } from '@testing-library/react';
import React from 'react';
import Posts from '.';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

describe('Post component', () => {
  test('component rendering & loadPosts function calling', () => {
    const loadPosts = jest.fn().mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Posts loadPosts={loadPosts} />);
    expect(screen.getByText(/PostList/i)).toBeInTheDocument();
    expect(loadPosts).toHaveBeenCalled();
  });

  it('renders all loaded posts', () => {
    const loadPosts = jest.fn().mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Posts loadPosts={loadPosts} />);
  });
});
